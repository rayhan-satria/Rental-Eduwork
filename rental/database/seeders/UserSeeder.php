<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // seed user with role 'admin'
        \App\Models\User::factory()->create([
            'name' => 'Admin Rental',
            'email' => 'admin@rental.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_verified' => true,
        ]);
        
        // seed user with role 'customer'
        \App\Models\User::factory()->create([
            'name' => 'Customer Rental',
            'email' => 'customer@rental.test',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'is_verified' => true,
        ]);

    }
}
