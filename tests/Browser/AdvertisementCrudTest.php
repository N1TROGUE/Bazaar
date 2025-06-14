<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\AdvertisementCategory;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdvertisementCrudTest extends DuskTestCase
{

    public function test_user_can_create_advertisement()
    {
        $user = User::factory()->create(['role_id' => 3]);

        $this->browse(function (Browser $browser) use ($user) {

            $expiration = now()->addDays(10)->format('Y-m-d\TH:i');

            $browser->loginAs($user)
                ->visit(route('advertisements.create'))
                ->type('title', 'Test Ad')
                ->type('description', 'Test Description')
                ->select('advertisement_category_id', 1)
                ->type('price', '123.45')
                ->select('ad_type', 'sale')
                ->attach('image_path', public_path('storage/advert_images/default.jpg'))
                ->script([
                    "document.getElementById('expiration_date').value = '{$expiration}';"
                ]);

                $browser
                ->press(__('advertisements.save_button'))
                ->assertSee('U heeft successvol een advertentie geplaatst.');
        });
    }

    public function test_user_can_view_advertisement()
    {
        $user = User::factory()->create(['role_id' => 3]);
        $ad = $user->advertisements()->create([
            'title' => 'View Me',
            'description' => 'Desc',
            'advertisement_category_id' => 1,
            'price' => 10,
            'ad_type' => 'sale',
            'image_path' => public_path('storage/advert_images/default.jpg'),
            'status' => 'active',
            'expiration_date' => now()->addDays(5),
        ]);

        $this->browse(function (Browser $browser) use ($user, $ad) {
            $browser->loginAs($user)
                ->visit(route('advertisements.show', $ad))
                ->assertSee('View Me')
                ->assertSee('Desc')
                ->assertSee('€10,00')
                ->assertSee(__('advertisements.reviews'))
                ->assertSee(__('advertisements.bidding'))
                ->assertSee(__('advertisements.buy'));
        });
    }
}
