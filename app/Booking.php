<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

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

    protected $appends = ['duration'];

    public function getDurationAttribute()
    {
        if ($this->start->format('Y-M-d') === $this->end->format('Y-M-d')) {
            return sprintf('%s to %s', $this->start->format('l d F o, H:i'), $this->end->format('H:i'));
        }

        return sprintf('%s to %s', $this->start->format('l d F o, H:i'), $this->end->format('l d F o, H:i'));
    }
}
