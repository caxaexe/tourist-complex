<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Booking;
use App\Http\Requests\StorePaymentRequest;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::query()
            ->with(['booking.client'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $bookingId = $request->query('booking_id');

        $bookings = Booking::with('client')
            ->orderBy('id', 'desc')
            ->limit(200)
            ->get();

        return view('payments.create', compact('bookings', 'bookingId'));
    }

    public function store(StorePaymentRequest $request)
    {
        $data = $request->validated();

        if (empty($data['paid_at'])) {
            $data['paid_at'] = now();
        }

        Payment::create($data);

        return redirect()->route('payments.index')
            ->with('success', 'Оплата добавлена');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Оплата удалена');
    }
}
