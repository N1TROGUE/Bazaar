<?php

namespace Tests\Browser;

use App\Models\Role; // Belangrijk om te importeren!
use App\Models\User; // Belangrijk om te importeren!
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Hash; // Nodig voor wachtwoord hashing

class RegisterTest extends DuskTestCase
{
    //use RefreshDatabase; 

    public function testUserCanRegisterSuccessfully(): void
    {
        $role = Role::firstOrCreate(['name' => 'gebruiker'], ['id' => 2]); 

        $this->browse(function (Browser $browser) use ($role) {
            $browser->visit('/register') 
                    ->type('name', 'Test User') 
                    ->type('email', 'newuser@example.com') 
                    ->type('password', 'MijnSterkeWachtwoord123!') 
                    ->type('password_confirmation', 'MijnSterkeWachtwoord123!') 
                    ->select('role_id', $role->id) 
                    ->press('Account aanmaken') 
                    ->assertPathIs('/index') 
                    ->assertSee('Uitloggen');
        });

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'name' => 'Test User',
            'role_id' => $role->id,
        ]);
    }

}