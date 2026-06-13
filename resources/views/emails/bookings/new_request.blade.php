<!DOCTYPE html>
<html>
<head>
    <title>Новая заявка на бронирование</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #030712; color: #e5e7eb; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #111827; padding: 30px; border-radius: 8px; border: 1px solid #1f2937;">
        
        <h2 style="color: #60a5fa; margin-top: 0; border-bottom: 1px solid #1f2937; padding-bottom: 10px;">
            Поступила новая заявка!
        </h2>
        
        <p style="color: #9ca3af; font-size: 16px;">Уважаемый администратор,</p>
        <p style="color: #d1d5db; font-size: 15px; line-height: 1.6;">
            В системе <strong>Castle Noctem</strong> зарегистрирована новая заявка на бронирование от гостя.
        </p>
        
        <div style="background-color: #030712; padding: 20px; border-radius: 6px; border: 1px solid #1f2937; margin: 20px 0;">
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="margin-bottom: 10px;">
                    <span style="color: #9ca3af;">Клиент:</span> 
                    <strong style="color: #f3f4f6;">{{ $booking->client->full_name ?? 'Гость' }}</strong>
                </li>
                <li style="margin-bottom: 10px;">
                    <span style="color: #9ca3af;">Телефон:</span> 
                    <strong style="color: #f3f4f6;">{{ $booking->client->phone ?? 'Не указан' }}</strong>
                </li>
                <li style="margin-bottom: 10px;">
                    <span style="color: #9ca3af;">Email:</span> 
                    <strong style="color: #f3f4f6;">{{ $booking->client->email ?? 'Не указан' }}</strong>
                </li>
                <li style="margin-bottom: 10px; padding-top: 10px; border-top: 1px dashed #374151;">
                    <span style="color: #9ca3af;">Покои:</span> 
                    <strong style="color: #60a5fa;">
                        {{ $booking->room->title ?? 'Покои Замка' }} (Комната №{{ $booking->room->number ?? '—' }})
                    </strong>
                </li>
                <li style="margin-bottom: 10px;">
                    <span style="color: #9ca3af;">Заезд:</span> 
                    <strong style="color: #f3f4f6;">{{ \Carbon\Carbon::parse($booking->date_from)->format('d.m.Y') }}</strong>
                </li>
                <li style="margin-bottom: 10px;">
                    <span style="color: #9ca3af;">Выезд:</span> 
                    <strong style="color: #f3f4f6;">{{ \Carbon\Carbon::parse($booking->date_to)->format('d.m.Y') }}</strong>
                </li>

                @if(!empty($booking->note))
                    <li style="margin-top: 15px; padding-top: 10px; border-top: 1px dashed #374151;">
                        <span style="color: #9ca3af; display: block; margin-bottom: 6px; font-size: 14px;">Пожелания гостя:</span>
                        <div style="color: #d1d5db; font-style: italic; background-color: #111827; padding: 12px; border-radius: 4px; border: 1px solid #1f2937; line-height: 1.5; font-size: 14px;">
                            {{ $booking->note }}
                        </div>
                    </li>
                @endif
            </ul>
        </div>

        <p style="color: #9ca3af; font-size: 14px; margin-top: 20px;">
            Пожалуйста, проверьте панель управления, чтобы связаться с клиентом и подтвердить его пребывание.
        </p>
    </div>
</body>
</html>