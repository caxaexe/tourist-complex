<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    protected $fillable = ['name'];

    public function rooms()
    {
        return $this->belongsToMany(\App\Models\Room::class, 'room_amenity');
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class);
    }
}
