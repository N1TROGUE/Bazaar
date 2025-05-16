<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdvertisementCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('advertisement_categories')->insert([
            [
                'name' => 'Electronica',
            ],
            [
                'name' => 'Meubilair',
            ],
            [
                'name' => 'Voertuigen',
            ],
            [
                'name' => 'Kleding',
            ],
            [
                'name' => 'Sport',
            ],
            [
                'name' => 'Huis en Tuin',
            ],
            [
                'name' => 'Diensten',
            ]
        ]);
    }
}
