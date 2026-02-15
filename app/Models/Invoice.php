<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'booking_id',
        'number',
        'issued_at',
        'due_at',
        'status',
        'total',
        'note',
    ];

    protected $casts = [
        'issued_at' => 'date',
        'due_at' => 'date',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
