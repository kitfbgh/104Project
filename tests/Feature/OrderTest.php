<?php

namespace Tests\Feature;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use WithoutMiddleware;
    use RefreshDatabase;

    /**
     * @throws BindingResolutionException
     */
    public function setUp(): void
    {
        Carbon::setTestNow('2021-03-23 14:00:00');
        parent::setUp();
    }

    public function testIndexSuccess()
    {
        $this->demoAdminLoginIn();
        $response = $this->call('GET', '/orders');
        $this->assertEquals(200, $response->status());
    }

    public function testIndexFaild()
    {
        $this->demoUserLoginIn();
        $response = $this->call('GET', '/orders');
        $this->assertEquals(302, $response->status());
    }

    public function testOrderDetailSuccess()
    {
        $this->demoAdminLoginIn();
        Order::create([
            'billing_email' => 'test@test.com',
            'billing_name' => 'test',
            'billing_phone' => '1231231231',
            'billing_address' => 'test',
            'user_id' => 4,
            'billing_subtotal' => '0',
            'billing_total' => '0',
            'billing_tax' => '0',
        ]);
        $response = $this->call('GET', '/orders/order/1');
        $this->assertEquals(200, $response->status());
    }

    public function testOrderDetailFaild()
    {
        $this->demoUserLoginIn();
        Order::create([
            'billing_email' => 'test@test.com',
            'billing_name' => 'test',
            'billing_phone' => '1231231231',
            'billing_address' => 'test',
            'user_id' => 4,
            'billing_subtotal' => '0',
            'billing_total' => '0',
            'billing_tax' => '0',
        ]);
        $response = $this->call('GET', '/orders/order/2');
        $this->assertEquals(302, $response->status());

        $this->demoAdminLoginIn();
        $response = $this->call('GET', '/orders/order/999');
        $this->assertEquals(404, $response->status());
    }

    public function testCheckoutSuccess()
    {
        $this->demoUserLoginIn();
        $response = $this->call('GET', '/orders/checkout');
        $this->assertEquals(200, $response->status());
    }

    public function testCheckoutFaild()
    {
        $response = $this->call('GET', '/orders/checkout');
        $this->assertEquals(500, $response->status());
    }

    public function testStoreSuccess()
    {
        $this->demoUserLoginIn();
        $response = $this->call('POST', '/orders', [
            'email' => 'test@test.com',
            'name' => 'test',
            'tel' => '1231231231',
            'address' => 'test',
            'userId' => 8,
            'subTotal' => '0',
            'total' => '0',
            'tax' => '0',
        ]);
        $this->assertEquals(302, $response->status());
    }

    public function testStoreFaild()
    {
        $this->demoUserLoginIn();
        $response = $this->call('POST', '/orders', [
            'billing_phone' => '1231231231',
            'billing_address' => 'test',
            'user_id' => 4,
            'billing_subtotal' => '0',
            'billing_total' => '0',
            'billing_tax' => '0',
        ]);
        $this->assertEquals(500, $response->status());
    }

    public function testUpdateSuccess()
    {
        Order::create([
            'billing_email' => 'test@test.com',
            'billing_name' => 'test',
            'billing_phone' => '1231231231',
            'billing_address' => 'test',
            'user_id' => 4,
            'billing_subtotal' => '0',
            'billing_total' => '0',
            'billing_tax' => '0',
        ]);
        $response = $this->call('PATCH', '/orders/4', [
            'status' => 'test',
        ]);
        $this->assertEquals(302, $response->status());
    }

    public function testUpdateFaild()
    {
        $response = $this->call('PATCH', '/orders/999', [
            'status' => 'test',
        ]);
        $this->assertEquals(500, $response->status());
    }

    public function testDestroySuccess()
    {
        Order::create([
            'billing_email' => 'test@test.com',
            'billing_name' => 'test',
            'billing_phone' => '1231231231',
            'billing_address' => 'test',
            'user_id' => 4,
            'billing_subtotal' => '0',
            'billing_total' => '0',
            'billing_tax' => '0',
        ]);
        $response = $this->call('DELETE', '/orders/5');
        $this->assertEquals(302, $response->status());
    }

    public function testDestroyFaild()
    {
        $response = $this->call('DELETE', '/orders/999');
        $this->assertEquals(500, $response->status());
    }
}
