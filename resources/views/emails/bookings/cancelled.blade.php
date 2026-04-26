<div>
    <h2>Здравствуйте, {{ $booking->client->full_name }}!</h2>
    <p>К сожалению, ваше бронирование (с {{ $booking->date_from->format('d.m.Y') }} по {{ $booking->date_to->format('d.m.Y') }}) было отменено.</p>
    <p><strong>Причина отмены:</strong> {{ $reason }}</p>
    <p>Если у вас есть вопросы, свяжитесь с нами.</p>
</div>