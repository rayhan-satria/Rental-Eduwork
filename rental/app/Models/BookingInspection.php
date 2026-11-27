<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookingInspection extends Model
{
    protected $fillable = [
        'booking_id',
        'inspection_type',
        'inspected_by',
        'condition_notes',
        'odometer',
        'fuel_level',
        'photo_path',
        'inspection_date',
    ];

    protected function casts(): array
    {
        return [
            'odometer' => 'integer',
            'inspection_date' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspected_by');
    }

    public function damageReports(): HasMany
    {
        return $this->hasMany(DamageReport::class);
    }
}
