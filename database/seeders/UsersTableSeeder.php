<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::factory()->create([
         'name' => 'Charaf Oualid',
         'email' => 'admin@bazaar.local',
         'password' => Hash::make('SterkWachtwoord123!'),
         'slug' => 'co',
         'role_id' => 4, // role of admin
         'email_verified_at' => now(),
        ]);

        // Particuliere gebruiker
        User::factory()->create([
            'name' => 'Mohammed Bogatyrev',
            'email' => 'particulier@bazaar.local',
            'password' => Hash::make('SterkWachtwoord123!'),
            'slug' => 'mb',
            'role_id' => 2, // role of particulier,
            'email_verified_at' => now(),
        ]);
    }
}
