<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class UserTest extends TestCase
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

    public function testUserOrderSuccess()
    {
        $this->demoUserLoginIn();
        $response = $this->call('GET', '/orders/user/21');
        $this->assertEquals(200, $response->status());
    }

    public function testUserOrderFaild()
    {
        $this->demoUserLoginIn();
        $response = $this->call('GET', '/orders/user/999');
        $this->assertEquals(302, $response->status());
    }

    public function testUserOrderDetailSuccess()
    {
        $this->demoUserLoginIn();
        $product = Product::create([
            'name' => 'testDestroySuccess',
            'price' => '123',
            'quantity' => '123',
            'category' => 'testStore',
            'origin_price' => '123',
            'unit' => '個',
        ]);
        // add the product to cart
        $data = \Cart::session(21)->add(array(
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
            'attributes' => array(
                'imageUrl' => $product->imageUrl,
                'image' => $product->image,
                'unit' => $product->unit,
                'size' => 'X',
            ),
            'associatedModel' => $product,
        ));
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
        $response = $this->call('GET', '/orders/6/detail');
        $this->assertEquals(200, $response->status());
    }

    public function testUserOrderDetailFaild()
    {
        $response = $this->call('GET', '/orders/999/detail');
        $this->assertEquals(404, $response->status());
    }

    public function testProfileSuccess()
    {
        $this->demoUserLoginIn();
        $response = $this->call('GET', '/profile');
        $this->assertEquals(200, $response->status());
    }

    public function testProfileFaild()
    {
        $response = $this->call('GET', '/profile');
        $this->assertEquals(500, $response->status());
    }

    public function testProductDetailSuccess()
    {
        $product = Product::create([
            'name' => 'testDestroySuccess',
            'price' => '123',
            'quantity' => '123',
            'category' => 'testStore',
            'origin_price' => '123',
            'unit' => '個',
        ]);
        $response = $this->call('GET', '/products/6');
        $this->assertEquals(200, $response->status());
    }

    public function testProductDetailFaild()
    {
        $response = $this->call('GET', '/products/999');
        $this->assertEquals(404, $response->status());
    }

    public function testUpdateSuccess()
    {
        $this->demoUserLoginIn();
        $response = $this->call('PATCH', '/profile/25', [
            'name' => 'test123',
            'email' => 'test@test.com'
        ]);
        $this->assertEquals(302, $response->status());
    }

    public function testUpdateFaild()
    {
        $response = $this->call('PATCH', '/profile/999', [
            'name' => 'test123',
            'email' => 'test@test.com'
        ]);
        $this->assertEquals(404, $response->status());
    }
}
