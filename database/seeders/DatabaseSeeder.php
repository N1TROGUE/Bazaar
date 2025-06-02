<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesTableSeeder::class,
            UsersTableSeeder::class,
            AdvertisementCategorySeeder::class,
            AdvertisementSeeder::class,
            FavoriteAdvertSeeder::class,
            ReviewSeeder::class,
            OrderSeeder::class,
            RentalSeeder::class,
            RelatedAdvertisementSeeder::class
        ]);
    }
}
