<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Service extends Model
{
    protected $fillable = ['name', 'price'];

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_service')
            ->withPivot(['quantity', 'price'])
            ->withTimestamps();
    }

    /**
     * Аксессор для перевода названия услуги
     */
    public function getNameAttribute($value)
    {
        $lang = App::getLocale();
        if ($lang === 'ru' || empty($value)) return $value;

        $translations = [
            'en' => [
                'Вечерний чай у камина' => 'Evening tea by the fireplace',
                'Услуги прачечной' => 'Laundry service',
                'Трансфер из аэропорта' => 'Airport transfer',
                'Завтрак в номер' => 'Breakfast in room',
            ],
            'ro' => [
                'Вечерний чай у камина' => 'Ceai de seară lângă șemineu',
                'Услуги прачечной' => 'Servicii de spălătorie',
                'Трансфер из аэропорта' => 'Transfer de la aeroport',
                'Завтрак в номер' => 'Mic dejun în cameră',
            ]
        ];

        return $translations[$lang][$value] ?? $value;
    }
}