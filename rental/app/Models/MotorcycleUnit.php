<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotorcycleUnit extends Model
{
    // Daftar status unit dan labelnya dalam bahasa Indonesia
    public const STATUSES = [
        'available' => 'Tersedia',
        'rented' => 'Disewa',
        'maintenance' => 'Perawatan',
    ];
     
        public const CONDITIONS = [
        'Baik',
        'Perlu Servis',
        'Rusak',
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
    public function motorcycle()
    {
        return $this->belongsTo(Motorcycle::class);
    }
}