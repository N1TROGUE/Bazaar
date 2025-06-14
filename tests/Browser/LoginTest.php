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
    use RefreshDatabase; // Zorgt voor een schone database voor elke test

    /**
     * Test een succesvolle login.
     * User Story: Als gebruiker wil ik kunnen inloggen met geldige gegevens.
     *
     * @return void
     */
    public function testUserCanLoginSuccessfully(): void
    {
        $role = Role::firstOrCreate(['id' => 1], ['name' => 'geen']);

        // 1. Maak een testgebruiker aan in de database.
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id, 
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login') // 2. Ga naar de loginpagina
                    ->type('email', $user->email) // 3. Vul e-mail in
                    ->type('password', 'password') // 4. Vul wachtwoord in
                    ->press('Inloggen') // 5. Klik op de login-knop (pas de tekst aan als deze anders is)
                    ->assertSee('Je inloggegevens zijn incorrect.') // Controleer of deze foutmelding verschijnt
                    ->assertPathIs('/index') // 6. Controleer of de gebruiker naar de homepage wordt gestuurd (jouw route('index'))
                    ->assertSee('Uitloggen'); // 7. Controleer of de gebruiker is ingelogd (bijv. "Uitloggen" knop is zichtbaar)
        });
    }

    /**
     * Test login met een incorrect wachtwoord.
     * User Story: Als gebruiker wil ik niet kunnen inloggen met een onjuist wachtwoord.
     *
     * @return void
     */
    public function testUserCannotLoginWithInvalidPassword(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('correct-password'),
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'test@example.com')
                    ->type('password', 'incorrect-password') // Incorrect wachtwoord
                    ->press('Login')
                    ->assertPathIs('/login') // Moet op de loginpagina blijven
                    ->assertSee('Je inloggegevens zijn incorrect.'); // Controleer de foutmelding
        });
    }

    /**
     * Test login met een niet-bestaand e-mailadres.
     * User Story: Als gebruiker wil ik niet kunnen inloggen met een niet-bestaand e-mailadres.
     *
     * @return void
     */
    public function testUserCannotLoginWithNonExistentEmail(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'nonexistent@example.com') // Niet-bestaand e-mailadres
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/login')
                    ->assertSee('Je inloggegevens zijn incorrect.');
        });
    }

    /**
     * Test login met lege velden.
     * User Story: Als gebruiker wil ik niet kunnen inloggen als het e-mailadres of wachtwoord leeg is.
     *
     * @return void
     */
    public function testUserCannotLoginWithEmptyFields(): void
    {
        $this->browse(function (Browser $browser) {
            // Leeg email, leeg wachtwoord
            $browser->visit('/login')
                    ->press('Login')
                    ->assertPathIs('/login')
                    ->assertSee('E-mailadres is verplicht.')
                    ->assertSee('Wachtwoord is verplicht.');
        });
    }
    
    /**
     * Test dat een ingelogde gebruiker de loginpagina niet kan bezoeken.
     * User Story: Als ingelogde gebruiker wil ik de loginpagina niet kunnen bezoeken.
     *
     * @return void
     */
    public function testLoggedInUserCannotVisitLoginPage(): void
    {
        // Maak een dummy gebruiker aan
        $user = User::factory()->create(); 

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user) // Log de gebruiker in met Dusk's helper
                    ->visit('/login') // Probeer de loginpagina te bezoeken
                    ->assertPathIs('/'); // Verwacht redirect naar de homepage
        });
    }
}