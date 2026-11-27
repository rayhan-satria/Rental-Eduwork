<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Motorcycle extends Model
{
    protected $fillable = [
        'brand',
        'model',
        'category',
        'cc',
        'daily_price',
        'description',
        'image',
    ];

    // 1 jenis motor punya banyak unit fisik
    public function units(): HasMany
    {
        return $this->hasMany(MotorcycleUnit::class);
    }

    // Nama lengkap, misalnya "Honda Vario 160"
    public function getFullNameAttribute()
    {
        return $this->brand . ' ' . $this->model;
    }

    // Alamat foto (null kalau belum ada foto)
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}