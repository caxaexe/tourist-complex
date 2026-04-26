<div>
    <h2>Здравствуйте, {{ $booking->client->full_name }}!</h2>
    <p>Ваша заявка на бронирование успешно подтверждена.</p>
    <ul>
        <li>Номер: {{ $booking->room->number }}</li>
        <li>Даты: с {{ $booking->date_from->format('d.m.Y') }} по {{ $booking->date_to->format('d.m.Y') }}</li>
        <li>Сумма: {{ $booking->total }} симолеонов.</li>
    </ul>
    <p>Ждем вас!</p>
</div>