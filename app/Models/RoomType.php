<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class RoomType extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Получить перевод названия в зависимости от локали
     */
    public function getNameAttribute($value)
    {
        return $this->getTranslation($value, 'name');
    }

    /**
     * Получить перевод описания в зависимости от локали
     */
    public function getDescriptionAttribute($value)
    {
        return $this->getTranslation($value, 'description');
    }

    /**
     * Вспомогательный метод для поиска перевода
     */
    private function getTranslation($value, $field)
    {
        $lang = App::getLocale();
        if ($lang === 'ru') return $value;

        // Здесь хранятся переводы
        $translations = [
            'en' => [
                'name' => [
                    'Апартаменты в Башне' => 'Tower Apartments',
                    'Панорамный' => 'Panoramic',
                    'Классический' => 'Classic',
                ],
                'description' => [
                    'Двухуровневый номер с элементами старинного интерьера и камином.' => 'Duplex room with vintage interior elements and a fireplace.',
                    'Номер с высокими потолками и витражными окнами, выходящими на горные хребты.' => 'Room with high ceilings and stained-glass windows overlooking mountain ranges.',
                    'Уютный номер в духе викторианской эпохи с темным деревом и плотными портьерами.' => 'Cozy Victorian-style room with dark wood and heavy curtains.',
                ]
            ],
            'ro' => [
                'name' => [
                    'Апартаменты в Башне' => 'Apartamente în Turn',
                    'Панорамный' => 'Panoramic',
                    'Классический' => 'Clasic',
                ],
                'description' => [
                    'Двухуровневый номер с элементами старинного интерьера и камином.' => 'Cameră duplex cu elemente de interior vintage și șemineu.',
                    'Номер с высокими потолками и витражными окнами, выходящими на горные хребты.' => 'Cameră cu tavane înalte și ferestre cu vitralii cu vedere la crestele munților.',
                    'Уютный номер в духе викторианской эпохи с темным деревом и плотными портьерами.' => 'Cameră confortabilă în stil victorian, cu lemn întunecat și draperii groase.',
                ]
            ]
        ];

        return $translations[$lang][$field][$value] ?? $value;
    }
}