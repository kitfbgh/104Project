<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class Register extends UnauthenticatedPage
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/register';
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
            ->assertSee('註冊')
            ->assertSee('使用者名稱')
            ->assertInputValue('name', '')
            ->assertSee('E-Mail Address')
            ->assertInputValue('email', '')
            ->assertSee('Password')
            ->assertInputValue('password', '')
            ->assertSee('Confirm Password')
            ->assertInputValue('password_confirmation', '');
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
