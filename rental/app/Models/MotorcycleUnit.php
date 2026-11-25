<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotorcycleUnit extends Model
{
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