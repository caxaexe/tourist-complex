<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Бронирование подтверждено') }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #030712; color: #e5e7eb; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #111827; padding: 30px; border-radius: 8px; border: 1px solid #1f2937;">
        
        <h2 style="color: #34d399; margin-top: 0; border-bottom: 1px solid #1f2937; padding-bottom: 10px;">
            {{ __('Бронирование подтверждено') }}
        </h2>
        
        <p style="color: #9ca3af; font-size: 16px;">{{ __('Здравствуйте,') }} {{ $booking->client->full_name ?? __('Гость') }}!</p>
        <p style="color: #d1d5db; font-size: 15px; line-height: 1.6;">
            {{ __('Ваша заявка на бронирование в') }} <strong>Castle Noctem</strong> {{ __('успешно подтверждена.') }}
        </p>
        
        <div style="background-color: #030712; padding: 20px; border-radius: 6px; border: 1px solid #1f2937; margin: 20px 0;">
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="margin-bottom: 10px;">
                    <span style="color: #9ca3af;">{{ __('Покои:') }}</span> 
                    <strong style="color: #60a5fa;">
                        {{ $booking->room->roomType->name ?? __('Покои Замка') }} ({{ __('Комната №') }}{{ $booking->room->number ?? '—' }})
                    </strong>
                </li>
                <li style="margin-bottom: 10px;">
                    <span style="color: #9ca3af;">{{ __('Заезд:') }}</span> 
                    <strong style="color: #f3f4f6;">{{ \Carbon\Carbon::parse($booking->date_from)->format('d.m.Y') }}</strong>
                </li>
                <li style="margin-bottom: 10px;">
                    <span style="color: #9ca3af;">{{ __('Выезд:') }}</span> 
                    <strong style="color: #f3f4f6;">{{ \Carbon\Carbon::parse($booking->date_to)->format('d.m.Y') }}</strong>
                </li>
                <li style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #374151;">
                    <span style="color: #9ca3af;">{{ __('Сумма к оплате:') }}</span> 
                    <strong style="color: #34d399;">{{ number_format((float)$booking->total, 2, '.', ' ') }} {{ __('лей') }}</strong>
                </li>
            </ul>
        </div>

        <p style="color: #9ca3af; font-size: 14px; margin-top: 20px;">
            {{ __('Ждем вас! Если у вас возникнут вопросы по размещению, пожалуйста, свяжитесь с нами.') }}
        </p>
    </div>
</body>
</html>