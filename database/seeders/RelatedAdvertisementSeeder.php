<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RelatedAdvertisementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('advertisement_related_advertisement')->insert([
            [
                'advertisement_id' => 1,
                'related_advertisement_id' => 2,
            ],
            [
                'advertisement_id' => 1,
                'related_advertisement_id' => 3,
            ],
            [
                'advertisement_id' => 1,
                'related_advertisement_id' => 4,
            ]
        ]);
    }
}
