<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class Login extends UnauthenticatedPage
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/login';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param Browser $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertTitleContains('104服飾店')
            ->assertSee('104服飾店')
            ->assertSee('登入')
            ->assertSee('E-Mail Address')
            ->assertInputValue('email', '')
            ->assertSee('Password')
            ->assertInputValue('password', '')
            ->assertNotChecked('remember')
            ->assertSee('登入');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            //
        ];
    }
}
