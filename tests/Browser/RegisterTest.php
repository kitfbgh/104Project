<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Register;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
    use RefreshDatabase;

    /** @test */
    public function registerPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Register());
        });
    }

    /** @test */
    public function registerUser()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Register())
                ->type('name', 'Bobby Bouwmann')
                ->type('email', 'register@gmail.com')
                ->type('password', 'password')
                ->type('password_confirmation', 'password')
                ->press('註冊')
                ->assertPathIs('/');
        });

        $this->assertDatabaseHas('users', [
            'name' => 'Bobby Bouwmann',
            'email' => 'register@gmail.com'
        ]);
    }
}
