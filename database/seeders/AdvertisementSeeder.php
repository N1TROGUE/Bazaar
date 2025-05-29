<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdvertisementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('advertisements')->insert([
            [
                'user_id' => 2,
                'advertisement_category_id' => 1,
                'title' => 'Samsung Galaxy S21',
                'description' => 'In goede staat, met originele doos en accessoires.',
                'image_path' => 'advert_images/54508045378_d70064daed.jpg',
                'price' => 350,
                'ad_type' => 'sale',
                'allow_bids' => false,
                'rental_min_duration_hours' => null,
                'rental_max_duration_hours' => null,
                'status' => 'active',
                'expiration_date' => now()->addDays(30),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'advertisement_category_id' => 2,
                'title' => 'IKEA Ektorp Bank',
                'description' => 'Comfortabele bank in uitstekende staat.',
                'image_path' => 'advert_images/4522506244_f9cb4ccf52.jpg',
                'price' => 200,
                'ad_type' => 'sale',
                'allow_bids' => true,
                'rental_min_duration_hours' => null,
                'rental_max_duration_hours' => null,
                'status' => 'active',
                'expiration_date' => now()->addDays(30),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'advertisement_category_id' => 3,
                'title' => 'Volkswagen Golf',
                'description' => 'Zeer goed onderhouden auto, bouwjaar 2018.',
                'image_path' => 'advert_images/30193245810_7b7ff74cd5.jpg',
                'price' => 15000,
                'ad_type' => 'sale',
                'allow_bids' => true,
                'rental_min_duration_hours' => null,
                'rental_max_duration_hours' => null,
                'status' => 'active',
                'expiration_date' => now()->addDays(30),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'advertisement_category_id' => 4,
                'title' => 'Nike Air Max Schoenen',
                'description' => 'Maat 42, bijna niet gedragen.',
                'image_path' => 'advert_images/30212993894_85351f21ba.jpg',
                'price' => 80,
                'ad_type' => 'sale',
                'allow_bids' => true,
                'rental_min_duration_hours' => null,
                'rental_max_duration_hours' => null,
                'status' => 'active',
                'expiration_date' => now()->addDays(30),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'advertisement_category_id' => 5,
                'title' => 'Fiets te koop',
                'description' => 'Goede staat, met verlichting en slot.',
                'image_path' => 'advert_images/36906009863_625ce02e9f.jpg',
                'price' => 150,
                'ad_type' => 'sale',
                'allow_bids' => true,
                'rental_min_duration_hours' => null,
                'rental_max_duration_hours' => null,
                'status' => 'active',
                'expiration_date' => now()->addDays(30),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'advertisement_category_id' => 6,
                'title' => 'Tuinset te huur',
                'description' => 'Ideaal voor de zomer, inclusief parasol.',
                'image_path' => 'advert_images/6815821700_8224103d9e.jpg',
                'price' => 50,
                'ad_type' => 'rental',
                'allow_bids' => false,
                'rental_min_duration_hours' => 5,
                'rental_max_duration_hours' => 24,
                'status' => 'active',
                'expiration_date' => now()->addDays(30),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'advertisement_category_id' => 7,
                'title' => 'Huis te huur',
                'description' => 'Ruim huis met 3 slaapkamers en tuin.',
                'image_path' => 'advert_images/6970631088_f8a396cc6a.jpg',
                'price' => 1200,
                'ad_type' => 'rental',
                'allow_bids' => false,
                'rental_min_duration_hours' => 720,
                'rental_max_duration_hours' => 1680,
                'status' => 'active',
                'expiration_date' => now()->addDays(30),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
