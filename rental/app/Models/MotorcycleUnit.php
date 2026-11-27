<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MotorcycleUnit extends Model
{
    // Daftar status unit dan labelnya dalam bahasa Indonesia
    public const STATUSES = [
        'available' => 'Tersedia',
        'rented' => 'Disewa',
        'maintenance' => 'Perawatan',
    ];
     
        public const CONDITIONS = [
        'good' => 'Baik',
        'needs_repair' => 'Perlu Servis',
        'damaged' => 'Rusak',
    ];
    protected $fillable = [
        'motorcycle_id',
        'license_plate',
        'year',
        'color',
        'unit_condition',
        'status',
    ];

    // Setiap unit milik 1 jenis motor
    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}