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
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    private function routePrefix(): string
    {
        $u = auth()->user();
        return $u?->hasRole('admin') ? 'admin.' : 'staff.';
    }

    public function index(Request $request)
    {
        $payment = $request->query('payment'); // unpaid | partial | paid | null

        $query = Booking::query()
            ->with(['client', 'room.roomType', 'invoice'])
            ->withSum('payments', 'amount');

        if ($payment === 'unpaid') {
            $query->havingRaw('COALESCE(payments_sum_amount, 0) <= 0');
        } elseif ($payment === 'partial') {
            $query->havingRaw('total > 0 AND COALESCE(payments_sum_amount, 0) > 0 AND COALESCE(payments_sum_amount, 0) < total');
        } elseif ($payment === 'paid') {
            $query->havingRaw('total > 0 AND COALESCE(payments_sum_amount, 0) >= total');
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

        // total не должен приходить из формы
        unset($data['total']);

        return DB::transaction(function () use ($data) {

            // 1) проверка пересечений
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

            // 2) расчёт проживания
            $room = Room::findOrFail($data['room_id']);

            $from = Carbon::parse($data['date_from']);
            $to   = Carbon::parse($data['date_to']);

            $nights    = max(1, $from->diffInDays($to));
            $stayTotal = $nights * (float)$room->price_per_night;

            $data['total']  = $stayTotal;
            $data['status'] = $data['status'] ?? 'pending';

            $booking = Booking::create($data);

            logAudit('created', $booking, null, $booking->toArray());

            if ($booking->status === 'confirmed') {
                $this->ensureInvoiceForBooking($booking);
            }

            $prefix = $this->routePrefix();

            return redirect()->route($prefix . 'bookings.index')
                ->with('success', 'Бронирование создано');
        });
    }

    public function edit(Request $request, Booking $booking)
    {
        $booking->load([
            'client',
            'room.roomType',
            'services',
            'payments',
            'invoice',
        ]);

        $clients = Client::orderBy('full_name')->get();

        $rooms = Room::with('roomType')
            ->where('is_active', true)
            ->orderBy('number')
            ->get();

        $services = Service::orderBy('name')->get();

        $selectedServices = $booking->services
            ->keyBy('id')
            ->map(fn($s) => [
                'quantity' => (int)($s->pivot->quantity ?? 0),
                'price'    => (float)($s->pivot->price_snapshot ?? $s->price ?? 0),
            ])
            ->toArray();

        // Оплата/баланс
        $paidTotal = (float)$booking->payments->sum('amount');
        $dueTotal  = (float)($booking->invoice?->total ?? $booking->total ?? 0);
        $balance   = max(0, $dueTotal - $paidTotal);

        // Чтобы твой edit.blade.php (который копия index) НЕ падал:
        $payment = $request->query('payment');

        // мини-статистика (как в index)
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

        // таблица (если edit у тебя реально показывает список)
        $bookings = Booking::query()
            ->with(['client', 'room.roomType', 'invoice'])
            ->withSum('payments', 'amount')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $invoice = $booking->invoice;

        return view('bookings.edit', compact(
            'booking',
            'invoice',
            'bookings',
            'payment',
            'activeCount',
            'checkInToday',
            'checkOutToday',
            'confirmedCount',
            'sumTotal',
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

        // total не должен приходить из формы
        unset($data['total']);

        return DB::transaction(function () use ($request, $booking, $data) {

            $old = $booking->toArray();

            // 1) проверка пересечений (исключая текущую бронь)
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

            // 2) проживание
            $room = Room::findOrFail($data['room_id']);
            $from = Carbon::parse($data['date_from']);
            $to   = Carbon::parse($data['date_to']);

            $nights    = max(1, $from->diffInDays($to));
            $stayTotal = $nights * (float)$room->price_per_night;

            // 3) sync услуг (snapshot price)
            $sync = [];
            foreach ($request->input('services', []) as $row) {
                $serviceId = (int)($row['id'] ?? 0);
                $qty       = (int)($row['quantity'] ?? 0);

                if ($serviceId <= 0 || $qty <= 0) continue;

                $service = Service::find($serviceId);
                if (!$service) continue;

                $sync[$serviceId] = [
                    'quantity' => $qty,
                    'price_snapshot' => (float)$service->price,
                ];
            }

            // 4) обновляем бронь (без total)
            $booking->update($data);

            // 5) услуги
            $booking->services()->sync($sync);

            // 6) итог по услугам
            $booking->load('services');
            $servicesTotal = $booking->services->sum(function ($s) {
                $qty = (int)($s->pivot->quantity ?? 0);
                $price = (float)($s->pivot->price_snapshot ?? $s->price ?? 0);
                return $qty * $price;
            });

            $booking->update(['total' => $stayTotal + $servicesTotal]);

            // 7) авто-счёт при confirmed
            if ($booking->status === 'confirmed') {
                $this->ensureInvoiceForBooking($booking);
            }

            logAudit('updated', $booking, $old, $booking->fresh()->toArray());

            $prefix = $this->routePrefix();

            return redirect()->route($prefix . 'bookings.index')
                ->with('success', 'Бронирование обновлено');
        });
    }

    public function destroy(Booking $booking)
    {
        $booking->load('invoice');

        $invoice = $booking->invoice;
        if ($invoice) {
            if ($invoice->payments()->exists()) {
                return back()->withErrors(['delete' => 'Нельзя удалить бронь: по счету есть оплаты.']);
            }

            $invoice->items()->delete();
            $invoice->delete();
        }

        $booking->services()->detach();

        $old = $booking->toArray();
        $booking->delete();

        logAudit('deleted', $booking, $old, null);

        $prefix = $this->routePrefix();

        return redirect()->route($prefix . 'bookings.index')
            ->with('success', 'Бронирование удалено');
    }

    public function ensureInvoiceForBooking(Booking $booking): void
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

        // 1) проживание
        $nights    = max(1, $booking->date_from->diffInDays($booking->date_to));
        $stayPrice = (float)($booking->room->price_per_night ?? 0);
        $stayLine  = $nights * $stayPrice;

        $desc = 'Проживание (номер №' . $booking->room->number . ')';

        InvoiceItem::create([
            'invoice_id'  => $invoice->id,
            'type'        => 'stay',
            'title'       => $desc,
            'description' => $desc,
            'quantity'    => $nights,
            'unit_price'  => $stayPrice,
            'total'       => $stayLine,
        ]);

        // 2) услуги
        foreach ($booking->services as $service) {
            $qty = (int)($service->pivot->quantity ?? 0);
            if ($qty <= 0) continue;

            $unit = (float)($service->pivot->price_snapshot ?? $service->price ?? 0);
            $line = $qty * $unit;

            $desc = 'Услуга: ' . $service->name;

            InvoiceItem::create([
                'invoice_id'  => $invoice->id,
                'type'        => 'service',
                'title'       => $desc,
                'description' => $desc,
                'quantity'    => $qty,
                'unit_price'  => $unit,
                'total'       => $line,
            ]);
        }

        $invoice->update([
            'total' => $invoice->items()->sum('total'),
        ]);

        logAudit('created', $invoice, null, $invoice->toArray());
    }

    public function checkIn(Booking $booking)
    {
        if ($booking->status !== 'confirmed') {
            return back()->with('success', 'Заезд возможен только для confirmed');
        }

        $old = $booking->toArray();
        $booking->update(['status' => 'checked_in']);
        logAudit('updated', $booking, $old, $booking->fresh()->toArray());

        return back()->with('success', 'Гость заселён (checked_in)');
    }

    public function checkOut(Booking $booking)
    {
        if ($booking->status !== 'checked_in') {
            return back()->with('success', 'Выезд возможен только для checked_in');
        }

        return DB::transaction(function () use ($booking) {

            $old = $booking->toArray();
            $booking->update(['status' => 'checked_out']);
            logAudit('updated', $booking, $old, $booking->fresh()->toArray());

            // закрываем счет, если paid
            $invoice = Invoice::where('booking_id', $booking->id)
                ->with('payments')
                ->first();

            if ($invoice) {
                $paid = (float)$invoice->payments->sum('amount');
                $due  = (float)$invoice->total;

                if ($due > 0 && $paid + 0.01 >= $due) {
                    $iOld = $invoice->toArray();
                    $invoice->update(['status' => 'closed']);
                    logAudit('updated', $invoice, $iOld, $invoice->fresh()->toArray());
                }
            }

            return back()->with('success', 'Гость выселен (checked_out)');
        });
    }

    public function createInvoice(Booking $booking)
    {
        $prefix = $this->routePrefix();

        $booking->load('invoice');

        if ($booking->invoice) {
            return redirect()->route($prefix . 'invoices.show', $booking->invoice);
        }

        $this->ensureInvoiceForBooking($booking);

        $booking->refresh()->load('invoice');

        return redirect()->route($prefix . 'invoices.show', $booking->invoice)
            ->with('success', 'Счёт создан');
    }
}