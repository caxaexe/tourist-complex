<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'client_id',
        'room_id',
        'date_from',
        'date_to',
        'status',
        'total',
        'note',
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function services()
{
    return $this->belongsToMany(Service::class, 'booking_service')
        ->withPivot(['quantity', 'price'])
        ->withTimestamps();
}


}
