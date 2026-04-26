<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingCancelled extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
        public string $reason
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Отмена бронирования',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.bookings.cancelled',
        );
    }
}