<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $fillable = [
        'client_id',
        'start',
        'end',
        'notes',
    ];

    protected $dates = [
        'start',
        'end',
    ];

    protected $casts = [
        'start' => 'datetime:l d F o, G:i',
        'end' => 'datetime:l d F o, G:i',
    ];
}
