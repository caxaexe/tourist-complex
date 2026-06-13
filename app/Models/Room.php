<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Room extends Model
{
    protected $fillable = [
        'number',
        'room_type_id',
        'title',
        'capacity',
        'price_per_night',
        'description',
        'is_active',
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(\App\Models\Amenity::class, 'room_amenity');
    }

    public function bookings()
    {
        return $this->hasMany(\App\Models\Booking::class);
    }

    /**
     * Аксессор для перевода title
     */
    public function getTitleAttribute($value)
    {
        return $this->getTranslation($value, 'title');
    }

    /**
     * Аксессор для перевода description
     */
    public function getDescriptionAttribute($value)
    {
        return $this->getTranslation($value, 'description');
    }

    private function getTranslation($value, $field)
    {
        $lang = App::getLocale();
        if ($lang === 'ru' || empty($value)) return $value;

        $translations = [
            'en' => [
                'title' => [
                    'Звездный чердак' => 'Starry Attic',
                    'Окно в долину' => 'Window to the Valley',
                    'Уютный полумрак' => 'Cozy Twilight',
                    'Вершина Башни' => 'Tower Top',
                    'Вид на горы' => 'Mountain View',
                    'Уютный Классик' => 'Cozy Classic',
                ],
                'description' => [
                    // Сюда можно добавить описания, если они используются
                ]
            ],
            'ro' => [
                'title' => [
                    'Звездный чердак' => 'Mansarda Înstelată',
                    'Окно в долину' => 'Fereastră spre Vale',
                    'Уютный полумрак' => 'Amurg Confortabil',
                    'Вершина Башни' => 'Vârful Turnului',
                    'Вид на горы' => 'Vedere la Munți',
                    'Уютный Классик' => 'Clasic Confortabil',
                ],
                'description' => []
            ]
        ];

        return $translations[$lang][$field][$value] ?? $value;
    }
}