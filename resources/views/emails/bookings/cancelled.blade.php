<!DOCTYPE html>
<html>
<head>
    <title>Бронирование отменено</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #030712; color: #e5e7eb; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #111827; padding: 30px; border-radius: 8px; border: 1px solid #1f2937;">
        
        <h2 style="color: #f87171; margin-top: 0; border-bottom: 1px solid #1f2937; padding-bottom: 10px;">
            Бронирование отменено
        </h2>
        
        <p style="color: #9ca3af; font-size: 16px;">Здравствуйте, {{ $booking->client->full_name ?? 'Гость' }}!</p>
        <p style="color: #d1d5db; font-size: 15px; line-height: 1.6;">
            К сожалению, ваше бронирование в <strong>Castle Noctem</strong> было отменено.
        </p>
        
        <div style="background-color: #030712; padding: 20px; border-radius: 6px; border: 1px solid #1f2937; margin: 20px 0;">
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="margin-bottom: 10px;">
                    <span style="color: #9ca3af;">Планируемый заезд:</span> 
                    <strong style="color: #f3f4f6;">{{ \Carbon\Carbon::parse($booking->date_from)->format('d.m.Y') }}</strong>
                </li>
                <li style="margin-bottom: 10px;">
                    <span style="color: #9ca3af;">Планируемый выезд:</span> 
                    <strong style="color: #f3f4f6;">{{ \Carbon\Carbon::parse($booking->date_to)->format('d.m.Y') }}</strong>
                </li>
                <li style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #374151;">
                    <span style="color: #9ca3af;">Причина отмены:</span> 
                    <strong style="color: #f87171;">{{ $reason ?? 'Не указана' }}</strong>
                </li>
            </ul>
        </div>

        <p style="color: #9ca3af; font-size: 14px; margin-top: 20px;">
            Если произошла ошибка или вы хотите выбрать другие даты для поездки, просто свяжитесь с нами.
        </p>
    </div>
</body>
</html>