<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DamageReport extends Model
{
    protected $fillable = [
        'booking_inspection_id',
        'description',
        'estimated_cost',
        'final_cost',
    ];

    protected function casts(): array
    {
        return [
            'estimated_cost' => 'decimal:2',
            'final_cost' => 'decimal:2', 
        ];
    }

    public function bookingInspection(): BelongsTo
    {
        return $this->belongsTo(BookingInspection::class);
    }
}
