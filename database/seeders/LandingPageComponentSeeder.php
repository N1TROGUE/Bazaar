<?php

namespace Database\Seeders;

use App\Models\LandingPageComponent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LandingPageComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LandingPageComponent::create([
            'user_id' => 1,
            'component_type' => 'welcome_message',
            'data' => null,
            'is_active' => true,
        ]);

        LandingPageComponent::create([
            'user_id' => 1,
            'component_type' => 'advertisements',
            'data' => null,
            'is_active' => true,
        ]);

        LandingPageComponent::create([
            'user_id' => 1,
            'component_type' => 'favorites',
            'data' => null,
            'is_active' => true,
        ]);

        LandingPageComponent::create([
            'user_id' => 1,
            'component_type' => 'dashboard_image',
            'data' => null,
            'is_active' => true,
        ]);
    }
}
