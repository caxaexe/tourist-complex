<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'passport_series',
        'passport_number',
        'birth_date',
        'address',
    ];
}
