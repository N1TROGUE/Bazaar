<?php

namespace Tests\Browser;

use App\Models\Role;
use App\Models\User; // Importeer het User model
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    public function testUserCanLoginSuccessfully(): void
    {
        $this->browse(function (Browser $browser)  {
            $browser->visit('/login') 
                    ->type('email', 'admin@bazaar.local') 
                    ->type('password', 'SterkWachtwoord123!') 
                    ->press('Inloggen')
                    ->assertPathIs('/index') 
                    ->assertSee('Uitloggen'); 
        });
    }

    public function testUserCannotLoginWithInvalidPassword(): void
    {

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'test@example.com')
                    ->type('password', 'incorrect-password') 
                    ->press('Inloggen')
                    ->assertPathIs('/login') 
                    ->assertSee('Je inloggegevens zijn incorrect.'); 
        });
    }

    public function testUserCannotLoginWithNonExistentEmail(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'nonexistent@example.com') 
                    ->type('password', 'password')
                    ->press('Inloggen')
                    ->assertPathIs('/login')
                    ->assertSee('Je inloggegevens zijn incorrect.');
        });
    }

}