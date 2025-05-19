<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Review::create([
            'user_id' => 1,
            'advertisement_id' => 1,
            'rating' => 5,
            'comment' => 'Geweldig product, zeker een aanrader!'
        ]);

        Review::create([
            'user_id' => 2,
            'advertisement_id' => 2,
            'rating' => 4,
            'comment' => 'Goede kwaliteit, maar een beetje duur.'
        ]);
    }
}
