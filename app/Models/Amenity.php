<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    protected $fillable = ['name'];

    public function rooms()
    {
        return $this->belongsToMany(Room::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class);
    }
}
