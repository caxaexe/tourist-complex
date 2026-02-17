<?php
/**
 * Правило пересечения:
 * Бронь конфликтует, если существует запись, где:
 * date_from < new_date_to
 * и date_to > new_date_from
 * и статус НЕ cancelled
 */

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Room;
use App\Models\Service;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $payment = $request->query('payment'); // unpaid | partial | paid | null

        $query = Booking::query()
            ->with(['client', 'room.roomType'])
            ->withSum('payments', 'amount');

        if ($payment === 'unpaid') {
            $query->havingRaw('COALESCE(payments_sum_amount, 0) <= 0');
        } elseif ($payment === 'partial') {
            $query->havingRaw('COALESCE(payments_sum_amount, 0) > 0 AND COALESCE(payments_sum_amount, 0) < total');
        } elseif ($payment === 'paid') {
            $query->havingRaw('COALESCE(payments_sum_amount, 0) >= total');
        }

        $bookings = $query->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Мини-статистика
        $today = now()->toDateString();

        $activeCount = Booking::whereNotIn('status', ['cancelled', 'checked_out'])->count();

        $checkInToday = Booking::whereDate('date_from', $today)
            ->where('status', '!=', 'cancelled')
            ->count();

        $checkOutToday = Booking::whereDate('date_to', $today)
            ->where('status', '!=', 'cancelled')
            ->count();

        $confirmedCount = Booking::where('status', 'confirmed')->count();

        $sumTotal = Booking::sum('total');

        return view('bookings.index', compact(
            'bookings',
            'payment',
            'activeCount',
            'checkInToday',
            'checkOutToday',
            'confirmedCount',
            'sumTotal'
        ));
    }

    public function create()
    {
        $clients = Client::orderBy('full_name')->get();

        $rooms = Room::with('roomType')
            ->where('is_active', true)
            ->orderBy('number')
            ->get();

        return view('bookings.create', compact('clients', 'rooms'));
    }

    public function store(StoreBookingRequest $request)
    {
        $data = $request->validated();

        $hasConflict = Booking::query()
            ->where('room_id', $data['room_id'])
            ->where('status', '!=', 'cancelled')
            ->where('date_from', '<', $data['date_to'])
            ->where('date_to', '>', $data['date_from'])
            ->exists();

        if ($hasConflict) {
            return back()
                ->withInput()
                ->withErrors(['date_from' => 'Номер уже занят на выбранные даты.']);
        }

        $room = Room::findOrFail($data['room_id']);

        $from = Carbon::parse($data['date_from']);
        $to = Carbon::parse($data['date_to']);

        $nights = $from->diffInDays($to);
        $total = $nights * (float)$room->price_per_night;

        $data['total'] = $total;
        $data['status'] = $data['status'] ?? 'pending';

        $booking = Booking::create($data);

        logAudit('created', $booking, null, $booking->toArray());

        return redirect()->route('bookings.index')
            ->with('success', 'Бронирование создано');
    }

    public function edit(Booking $booking)
    {
        $booking->load([
            'client',
            'room.roomType',
            'services',
            'payments',
        ]);

        $clients = Client::orderBy('full_name')->get();

        $rooms = Room::with('roomType')
            ->where('is_active', true)
            ->orderBy('number')
            ->get();

        $services = Service::orderBy('name')->get();

        // pivot может НЕ иметь price — тогда берём текущую цену услуги
        $selectedServices = $booking->services
            ->keyBy('id')
            ->map(fn($s) => [
                'quantity' => (int)($s->pivot->quantity ?? 0),
                'price'    => (float)($s->pivot->price ?? $s->price ?? 0),
            ])
            ->toArray();

        $paidTotal = (float)$booking->payments->sum('amount');
        $dueTotal  = (float)$booking->total;
        $balance   = max(0, $dueTotal - $paidTotal);

        return view('bookings.edit', compact(
            'booking',
            'clients',
            'rooms',
            'services',
            'selectedServices',
            'paidTotal',
            'dueTotal',
            'balance'
        ));
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $data = $request->validated();
        $old  = $booking->toArray();

        $hasConflict = Booking::query()
            ->where('room_id', $data['room_id'])
            ->where('status', '!=', 'cancelled')
            ->where('id', '!=', $booking->id)
            ->where('date_from', '<', $data['date_to'])
            ->where('date_to', '>', $data['date_from'])
            ->exists();

        if ($hasConflict) {
            return back()
                ->withInput()
                ->withErrors(['date_from' => 'Номер уже занят на выбранные даты.']);
        }

        // проживание
        $room = Room::findOrFail($data['room_id']);
        $from = Carbon::parse($data['date_from']);
        $to   = Carbon::parse($data['date_to']);

        $nights = $from->diffInDays($to);
        $stayTotal = $nights * (float)$room->price_per_night;

        // sync услуг
        $sync = [];
        foreach ($request->input('services', []) as $row) {
            $serviceId = (int)($row['id'] ?? 0);
            $qty       = (int)($row['quantity'] ?? 0);

            if ($serviceId <= 0 || $qty <= 0) continue;

            $service = Service::find($serviceId);
            if (!$service) continue;

            // snapshot price (если pivot колонки нет — sync просто проигнорирует price, но не сломается)
            $sync[$serviceId] = [
                'quantity' => $qty,
                'price'    => (float)$service->price,
            ];
        }

        // update основных полей
        $booking->update($data);

        // услуги
        $booking->services()->sync($sync);

        // считаем услуги: если pivot.price нет — fallback на service.price
        $booking->load('services');
        $servicesTotal = $booking->services->sum(function ($s) {
            $qty = (int)($s->pivot->quantity ?? 0);
            $price = (float)($s->pivot->price ?? $s->price ?? 0);
            return $qty * $price;
        });

        $booking->update(['total' => $stayTotal + $servicesTotal]);

        // авто-счет при confirmed
        if ($booking->status === 'confirmed') {
            $this->ensureInvoiceForBooking($booking);
        }

        logAudit('updated', $booking, $old, $booking->fresh()->toArray());

        return redirect()->route('bookings.index')
            ->with('success', 'Бронирование обновлено');
    }

    public function destroy(Booking $booking)
    {
        if ($booking->status === 'confirmed') {
            return redirect()
                ->route('bookings.index')
                ->with('success', 'Удаление запрещено: бронирование подтверждено');
        }

        $old = $booking->toArray();
        logAudit('deleted', $booking, $old, null);

        $booking->delete();

        return redirect()->route('bookings.index')
            ->with('success', 'Бронирование удалено');
    }

    private function ensureInvoiceForBooking(Booking $booking): void
    {
        $exists = Invoice::where('booking_id', $booking->id)->exists();
        if ($exists) return;

        $booking->load(['room', 'services']);

        $invoice = Invoice::create([
            'booking_id' => $booking->id,
            'number'     => 'INV-' . now()->format('Ymd') . '-' . str_pad((string)$booking->id, 5, '0', STR_PAD_LEFT),
            'issued_at'  => now()->toDateString(),
            'due_at'     => now()->addDays(3)->toDateString(),
            'status'     => 'issued',
            'total'      => 0,
            'note'       => 'Авто-счёт при подтверждении бронирования',
        ]);

        $itemsTotal = 0;

        // проживание
        $nights = $booking->date_from->diffInDays($booking->date_to);
        $stayPrice = (float)($booking->room->price_per_night ?? 0);
        $stayLine  = $nights * $stayPrice;

        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'title'      => 'Проживание (номер №' . $booking->room->number . ')',
            'quantity'   => $nights,
            'unit_price' => $stayPrice,
            'line_total' => $stayLine,
        ]);

        $itemsTotal += $stayLine;

        // услуги
        foreach ($booking->services as $service) {
            $qty = (int)($service->pivot->quantity ?? 0);
            if ($qty <= 0) continue;

            $unit = (float)($service->pivot->price ?? $service->price ?? 0);
            $line = $qty * $unit;

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'title'      => 'Услуга: ' . $service->name,
                'quantity'   => $qty,
                'unit_price' => $unit,
                'line_total' => $line,
            ]);

            $itemsTotal += $line;
        }

        $invoice->update(['total' => $itemsTotal]);

        logAudit('created', $invoice, null, $invoice->toArray());
    }
}
