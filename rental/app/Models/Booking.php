<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'booking_code',
        'user_id',
        'motorcycle_unit_id',
        'start_date',
        'end_date',
        'actual_return_date',
        'hold_expires_at',
        'duration_days',
        'daily_price',
        'total_price',
        'late_fee',
        'damage_fee',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'actual_return_date' => 'datetime',
            'hold_expires_at' => 'datetime',
            'duration_days' => 'integer',
            'daily_price' => 'decimal:2',
            'total_price' => 'decimal:2',
            'late_fee' => 'decimal:2',
            'damage_fee' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function motorcycleUnit(): BelongsTo
    {
        return $this->belongsTo(MotorcycleUnit::class);
    }

    public function bookingInspections(): HasMany
    {
        return $this->hasMany(BookingInspection::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}