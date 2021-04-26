<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;

    /**
     * @throws BindingResolutionException
     */
    public function setUp(): void
    {
        Carbon::setTestNow('2021-03-23 14:00:00');
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    /**
     * 測試admin進入到後台頁面
     *
     * @return void
     */
    public function testIndexSuccess()
    {
        $this->demoAdminLoginIn();
        $response = $this->call('GET', '/dashboard');
        $this->assertEquals(200, $response->status());
    }

    public function testIndexFaild()
    {
        $this->demoUserLoginIn();
        $response = $this->call('GET', '/dashboard');
        $this->assertEquals(302, $response->status());
        $response->assertRedirect('/');
    }
}
