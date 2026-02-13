<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

}
