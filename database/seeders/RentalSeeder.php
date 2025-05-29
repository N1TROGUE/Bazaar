<?php

namespace Database\Seeders;

use App\Models\Rental;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rental::create([
            'advertisement_id' => 1,
            'user_id' => 1,
            'rented_from' => now()->subDays(7),
            'rented_until' => now(),
            'status' => 'active',
        ]);

        Rental::create([
            'advertisement_id' => 2,
            'user_id' => 1,
            'rented_from' => now()->subDays(10),
            'rented_until' => now(),
            'status' => 'active',
        ]);
    }
}
