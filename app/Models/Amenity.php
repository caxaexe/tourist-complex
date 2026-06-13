<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Amenity extends Model
{
    protected $fillable = ['name'];

    public function rooms()
    {
        return $this->belongsToMany(\App\Models\Room::class, 'room_amenity');
    }

    /**
     * Аксессор для перевода названия удобства
     */
    public function getNameAttribute($value)
    {
        $lang = App::getLocale();
        if ($lang === 'ru' || empty($value)) return $value;

        $translations = [
            'en' => [
                'Фен' => 'Hairdryer',
                'Мини-бар с локальными напитками' => 'Mini-bar with local drinks',
                'Телевизор с плоским экраном' => 'Flat-screen TV',
                'Камин в номере' => 'Fireplace in room',
            ],
            'ro' => [
                'Фен' => 'Uscător de păr',
                'Мини-бар с локальными напитками' => 'Mini-bar cu băuturi locale',
                'Телевизор с плоским экраном' => 'TV cu ecran plat',
                'Камин в номере' => 'Șemineu în cameră',
            ]
        ];

        return $translations[$lang][$value] ?? $value;
    }
}