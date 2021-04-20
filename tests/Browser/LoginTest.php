<?php

namespace Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Login;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    /** @test */
    public function loginPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Login());
        });
    }

    /** @test */
    public function loginUser()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit(new Login())
                ->type('email', $user->email)
                ->type('password', 'password')
                ->press('登入')
                ->assertPathIs('/login');
        });
    }
}
