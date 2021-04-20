<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProductDetailTest extends DuskTestCase
{
    /** @test */
    public function productDetailPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('');
        });
    }
}
