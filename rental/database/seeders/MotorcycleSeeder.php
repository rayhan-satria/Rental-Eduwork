<?php

namespace Database\Seeders;

use App\Models\Motorcycle;
use Illuminate\Database\Seeder;

class MotorcycleSeeder extends Seeder
{
    public function run(): void
    {
        $motorcycles = [
            [
                'data' => ['brand' => 'Honda', 'model' => 'Beat', 'category' => 'matic', 'cc' => 110,
                           'daily_price' => 80000, 'description' => 'Matic irit, cocok untuk dalam kota. Termasuk 1 helm.'],
                'units' => [
                    ['license_plate' => 'DK 1234 AB', 'year' => 2022, 'color' => 'Putih', 'unit_condition' => 'good'],
                    ['license_plate' => 'DK 2345 AB', 'year' => 2023, 'color' => 'Hitam', 'unit_condition' => 'good'],
                ],
            ],
            [
                'data' => ['brand' => 'Honda', 'model' => 'Vario 160', 'category' => 'matic', 'cc' => 160,
                           'daily_price' => 100000, 'description' => 'Matic bertenaga dengan bagasi luas. Termasuk 2 helm.'],
                'units' => [
                    ['license_plate' => 'DK 3456 CD', 'year' => 2023, 'color' => 'Merah', 'unit_condition' => 'good'],
                ],
            ],
            [
                'data' => ['brand' => 'Yamaha', 'model' => 'NMAX', 'category' => 'matic', 'cc' => 155,
                           'daily_price' => 150000, 'description' => 'Matic maxi, nyaman untuk perjalanan jauh. Termasuk 2 helm dan jas hujan.'],
                'units' => [
                    ['license_plate' => 'DK 4567 EF', 'year' => 2024, 'color' => 'Abu-abu', 'unit_condition' => 'good'],
                    ['license_plate' => 'DK 5678 EF', 'year' => 2022, 'color' => 'Biru', 'unit_condition' => 'good', 'status' => 'maintenance'],
                ],
            ],
            [
                'data' => ['brand' => 'Honda', 'model' => 'Supra X 125', 'category' => 'manual', 'cc' => 125,
                           'daily_price' => 70000, 'description' => 'Motor bebek manual yang tangguh dan hemat.'],
                'units' => [
                    ['license_plate' => 'DK 6789 GH', 'year' => 2021, 'color' => 'Hitam', 'unit_condition' => 'needs_repair'],
                ],
            ],
            [
                'data' => ['brand' => 'Kawasaki', 'model' => 'Ninja 250', 'category' => 'sport', 'cc' => 250,
                           'daily_price' => 300000, 'description' => 'Motor sport untuk pengalaman berkendara maksimal.'],
                'units' => [],
            ],
        ];

        foreach ($motorcycles as $item) {
            $motorcycle = Motorcycle::create($item['data']);

            foreach ($item['units'] as $unit) {
                $motorcycle->units()->create($unit);
            }
        }
    }
}