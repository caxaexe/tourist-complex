<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Room;
use App\Models\Service;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmed;
use App\Mail\BookingCancelled;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    protected function getRoutePrefix() 
    { 
        return auth()->user()->hasRole('admin') ? 'admin.' : 'staff.'; 
    }

    public function index(Request $request)
    {
        $payment = $request->query('payment');
        $query = Booking::query()
            ->with(['client', 'room.roomType', 'invoice'])
            ->withSum('payments', 'amount');

        if ($payment === 'unpaid') $query->havingRaw('COALESCE(payments_sum_amount, 0) <= 0');
        elseif ($payment === 'partial') $query->havingRaw('total > 0 AND COALESCE(payments_sum_amount, 0) > 0 AND COALESCE(payments_sum_amount, 0) < total');
        elseif ($payment === 'paid') $query->havingRaw('total > 0 AND COALESCE(payments_sum_amount, 0) >= total');

        $bookings = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        
        $activeCount = Booking::whereNotIn('status', ['cancelled', 'checked_out'])->count();
        $confirmedCount = Booking::where('status', 'confirmed')->count();
        $sumTotal = Booking::sum('total');

        return view('bookings.index', compact('bookings', 'payment', 'activeCount', 'confirmedCount', 'sumTotal'));
    }

    public function create()
    {
        $clients = Client::orderBy('full_name')->get();
        $rooms = Room::with('roomType')->where('is_active', true)->orderBy('number')->get();
        return view('bookings.create', compact('clients', 'rooms'));
    }

    public function store(StoreBookingRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validated();
            
            $hasConflict = Booking::query()
                ->where('room_id', $data['room_id'])
                ->where('status', '!=', 'cancelled')
                ->where('date_from', '<', $data['date_to'])
                ->where('date_to', '>', $data['date_from'])
                ->exists();

            if ($hasConflict) return back()->withInput()->withErrors(['date_from' => 'Номер занят на эти даты']);

            $room = Room::findOrFail($data['room_id']);
            $nights = max(1, Carbon::parse($data['date_from'])->diffInDays(Carbon::parse($data['date_to'])));
            $data['total'] = $nights * (float)$room->price_per_night;
            $data['status'] = $data['status'] ?? 'pending';

            $booking = Booking::create($data);
            if (function_exists('logAudit')) logAudit('created', $booking, null, $booking->toArray());

            if ($booking->status === 'confirmed') $this->ensureInvoiceForBooking($booking);

            return redirect()->route($this->getRoutePrefix() . 'bookings.index')->with('success', 'Создано');
        });
    }

    public function edit(Booking $booking)
    {
        $booking->load(['client', 'room.roomType', 'services', 'invoice.payments', 'payments']);
        $clients = Client::orderBy('full_name')->get();
        $rooms = Room::with('roomType')->where('is_active', true)->orderBy('number')->get();
        $services = Service::orderBy('name')->get();

        return view('bookings.edit', compact('booking', 'clients', 'rooms', 'services'));
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        return DB::transaction(function () use ($request, $booking) {
            $data = $request->validated();
            $room = Room::findOrFail($data['room_id']);
            $nights = max(1, Carbon::parse($data['date_from'])->diffInDays(Carbon::parse($data['date_to'])));
            $data['total'] = $nights * (float)$room->price_per_night;

            $old = $booking->toArray();
            $booking->update($data);
            if (function_exists('logAudit')) logAudit('updated', $booking, $old, $booking->fresh()->toArray());

            if ($booking->status === 'confirmed') $this->ensureInvoiceForBooking($booking);

            return redirect()->route($this->getRoutePrefix() . 'bookings.index')->with('success', 'Обновлено');
        });
    }

    public function destroy(Booking $booking)
    {
        if ($booking->invoice) {
            $booking->invoice->items()->delete();
            $booking->invoice->delete();
        }
        $booking->services()->detach();
        $booking->delete();
        return redirect()->route($this->getRoutePrefix() . 'bookings.index')->with('success', 'Удалено');
    }

    public function confirm(Booking $booking)
    {
        $booking->update(['status' => 'confirmed']);
        
        $this->ensureInvoiceForBooking($booking);

        if ($booking->client && $booking->client->email) {
            try {
                Mail::to($booking->client->email)->send(new \App\Mail\BookingConfirmed($booking));
            } catch (\Exception $e) {
                \Log::error("Ошибка отправки письма подтверждения: " . $e->getMessage());
                return back()->with('error', 'Бронирование подтверждено, но не удалось отправить письмо.');
            }
        }

        return back()->with('success', 'Бронирование подтверждено, письмо отправлено.');
    }

    public function cancel(Request $request, Booking $booking)
    {
        $booking->update(['status' => 'cancelled']);
        return back()->with('success', 'Отменено');
    }

    public function checkIn(Booking $booking) { $booking->update(['status' => 'checked_in']); return back(); }
    public function checkOut(Booking $booking) { $booking->update(['status' => 'checked_out']); return back(); }

    protected function ensureInvoiceForBooking(Booking $booking): void
    {
        if (Invoice::where('booking_id', $booking->id)->exists()) return;

        $booking->load(['room', 'services']);
        $invoice = Invoice::create([
            'booking_id' => $booking->id,
            'number' => 'INV-' . now()->format('Ymd') . '-' . str_pad($booking->id, 5, '0', STR_PAD_LEFT),
            'issued_at' => now()->toDateString(),
            'due_at' => now()->addDays(3)->toDateString(),
            'status' => 'issued',
            'total' => 0,
        ]);

        $nights = max(1, $booking->date_from->diffInDays($booking->date_to));
        $stayPrice = (float)($booking->room->price_per_night ?? 0);
        $stayTitle = 'Проживание №' . $booking->room->number;
        
        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'type' => 'stay',
            'title' => $stayTitle,
            'description' => $stayTitle, // Добавлено поле description
            'quantity' => $nights,
            'unit_price' => $stayPrice,
            'total' => $nights * $stayPrice,
        ]);

        foreach ($booking->services as $service) {
            $desc = 'Услуга: ' . $service->name;
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'type' => 'service',
                'title' => $desc,
                'description' => $desc, // Добавлено поле description
                'quantity' => $service->pivot->quantity,
                'unit_price' => (float)$service->pivot->price_snapshot,
                'total' => $service->pivot->quantity * (float)$service->pivot->price_snapshot,
            ]);
        }
        $invoice->update(['total' => $invoice->items()->sum('total')]);
    }
}