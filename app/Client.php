<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'postcode',
        'user_id'
    ];

    protected $appends = ['url'];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class)->orderBy('start');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function journals(): HasMany
    {
        return $this->hasMany(Journal::class);
    }

    public function getBookingsCountAttribute(): int
    {
        return $this->bookings()->count();
    }

    public function getUrlAttribute(): string
    {
        return sprintf('/clients/%d', $this->id);
    }
}
