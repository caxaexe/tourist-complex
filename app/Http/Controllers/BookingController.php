<?php
/**Правило пересечения (классика):
Бронь конфликтует, если существует запись, где:
date_from < new_date_to
и date_to > new_date_from
и статус НЕ cancelled */

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Room;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
{
    $bookings = Booking::query()
        ->with(['client', 'room.roomType'])
        ->withSum('payments', 'amount') // даст поле payments_sum_amount
        ->orderBy('id', 'desc')
        ->paginate(10);

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

        // 2) расчёт total = nights * price_per_night
        $room = Room::findOrFail($data['room_id']);

        $from = Carbon::parse($data['date_from']);
        $to = Carbon::parse($data['date_to']);

        $nights = $from->diffInDays($to); // date_to > date_from, значит >=1
        $total = $nights * (float)$room->price_per_night;

        $data['total'] = $total;
        $data['status'] = $data['status'] ?? 'pending';

        Booking::create($data);

        return redirect()->route('bookings.index')
            ->with('success', 'Бронирование создано');
    }

public function edit(Booking $booking)
{
    // Подгружаем связи заранее (чтобы не было N+1 и чтобы работало в view)
    $booking->load([
        'client',
        'room.roomType',
        'services',   // pivot quantity/price
        'payments',   // оплаты
    ]);

    $clients = Client::orderBy('full_name')->get();

    $rooms = Room::with('roomType')
        ->where('is_active', true)
        ->orderBy('number')
        ->get();

    $services = Service::orderBy('name')->get();

    // [service_id => ['quantity'=>..., 'price'=>...]]
    $selectedServices = $booking->services
        ->keyBy('id')
        ->map(fn ($s) => [
            'quantity' => (int)$s->pivot->quantity,
            'price' => (float)$s->pivot->price,
        ])
        ->toArray();

    // Мини-статистика по оплатам для конкретной брони
    $paidTotal = (float)$booking->payments->sum('amount');
    $dueTotal = (float)$booking->total;
    $balance = max(0, $dueTotal - $paidTotal);

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

        $room = Room::findOrFail($data['room_id']);
        $from = Carbon::parse($data['date_from']);
        $to = Carbon::parse($data['date_to']);
        $nights = $from->diffInDays($to);
        $data['total'] = $nights * (float)$room->price_per_night;

        $booking->update($data);
        // Собираем sync массив: [service_id => ['quantity'=>X, 'price'=>snapshotPrice]]
        $sync = [];

        foreach ($request->input('services', []) as $row) {

        $serviceId = (int)$row['id'];
        $qty = (int)$row['quantity'];

        $service = Service::find($serviceId);
        if (!$service) continue;

        $sync[$serviceId] = [
            'quantity' => $qty,
            'price' => (float)$service->price,
        ];
    }

        $booking->services()->sync($sync);

        $room = Room::findOrFail($data['room_id']);
        $from = \Carbon\Carbon::parse($data['date_from']);
        $to = \Carbon\Carbon::parse($data['date_to']);

        $nights = $from->diffInDays($to);
        $stayTotal = $nights * (float)$room->price_per_night;

        $servicesTotal = $booking->services()
            ->get()
            ->sum(fn($s) => $s->pivot->quantity * $s->pivot->price);

        $booking->update([
            'total' => $stayTotal + $servicesTotal
        ]);


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

    $booking->delete();

    return redirect()->route('bookings.index')
        ->with('success', 'Бронирование удалено');
}   
}
