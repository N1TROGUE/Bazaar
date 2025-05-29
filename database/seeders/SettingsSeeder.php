<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Settings::create([
            'logo_path' => 'https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500',
            'nav_color' => 'bg-gray-800',       // standaardkleur uit jouw layout
            'button_color' => 'bg-indigo-600',  // standaardkleur uit jouw layout
        ]);
    }
}
