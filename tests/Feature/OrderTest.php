<?php

namespace Tests\Feature;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class OrderTest extends TestCase
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
     * 測試admin進入到admin的訂單頁面
     *
     * @return void
     */
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
        $response->assertRedirect('/');
    }

    /**
     * 測試admin進入到admin的詳細訂單頁面
     *
     * @return void
     */
    public function testOrderDetailSuccess()
    {
        $this->demoAdminLoginIn();
        $order = factory(Order::class)->create();
        $response = $this->call('GET', '/orders/order/1');
        $this->assertEquals(200, $response->status());
    }

    public function testOrderDetailFaild()
    {
        $this->demoUserLoginIn();
        $order = factory(Order::class)->create();
        $response = $this->call('GET', '/orders/order/2');
        $this->assertEquals(302, $response->status());
        $response->assertRedirect('/');

        $this->demoAdminLoginIn();
        $response = $this->call('GET', '/orders/order/999');
        $this->assertEquals(404, $response->status());
    }

    /**
     * 測試user進入到結帳頁面
     *
     * @return void
     */
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

    /**
     * 測試user提交訂單功能
     *
     * @return void
     */
    public function testStoreSuccess()
    {
        $this->demoUserLoginIn();
        $order = factory(Order::class)->create();
        $response = $this->call('POST', '/orders', [
            'email' => $order->billing_email,
            'name' => $order->billing_name,
            'tel' => $order->billing_phone,
            'address' => $order->billing_address,
            'userId' => $order->user_id,
            'subTotal' => $order->billing_subtotal,
            'total' => $order->billing_total,
            'tax' => $order->billing_tax,
            'payment' => $order->payment,
        ]);
        $this->assertEquals(302, $response->status());
        $response->assertRedirect('/orders/user/1');

        $this->demoAdminLoginIn();
        $response = $this->call('POST', '/orders', [
            'email' => $order->billing_email,
            'name' => $order->billing_name,
            'tel' => $order->billing_phone,
            'address' => $order->billing_address,
            'userId' => $order->user_id,
            'subTotal' => $order->billing_subtotal,
            'total' => $order->billing_total,
            'tax' => $order->billing_tax,
            'payment' => $order->payment,
        ]);
        $this->assertEquals(302, $response->status());
        $response->assertRedirect('/orders');
    }

    public function testStoreFaild()
    {
        // test user access
        $this->demoUserLoginIn();
        $response = $this->call('POST', '/orders', [
            'billing_phone' => '1231231231',
            'billing_address' => 'test',
            'user_id' => 4,
            'billing_subtotal' => '0',
            'billing_total' => '0',
            'billing_tax' => '0',
        ]);
        $this->assertEquals(422, $response->status());
    }

    /**
     * 測試訂單狀態更新
     *
     * @return void
     */
    public function testUpdateSuccess()
    {
        // test user access
        $this->demoUserLoginIn();
        $order = factory(Order::class)->create();
        $response = $this->call('PATCH', '/orders/1', [
            'status' => 'test',
        ]);
        $this->assertEquals(302, $response->status());
        $response->assertRedirect('/orders/1/detail');

        // test admin access
        $this->demoAdminLoginIn();
        $response = $this->call('PATCH', '/orders/1', [
            'status' => 'test1',
        ]);
        $this->assertEquals(302, $response->status());
        $response->assertRedirect('/orders/order/1');
    }

    public function testUpdateFaild()
    {
        $response = $this->call('PATCH', '/orders/999', [
            'status' => 'test',
        ]);
        $this->assertEquals(404, $response->status());
    }

    /**
     * 測試admin刪除訂單
     *
     * @return void
     */
    public function testDestroySuccess()
    {
        $order = factory(Order::class)->create();
        $response = $this->call('DELETE', '/orders/1');
        $this->assertEquals(302, $response->status());
        $response->assertRedirect('/orders');
    }

    public function testDestroyFaild()
    {
        $response = $this->call('DELETE', '/orders/999');
        $this->assertEquals(404, $response->status());
    }
}
