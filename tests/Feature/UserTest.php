<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class UserTest extends TestCase
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

    public function testUserOrderSuccess()
    {
        $this->demoUserLoginIn();
        $response = $this->call('GET', '/orders/user/1');
        $this->assertEquals(200, $response->status());
    }

    public function testUserOrderFaild()
    {
        $this->demoUserLoginIn();
        $response = $this->call('GET', '/orders/user/999');
        $this->assertEquals(302, $response->status());
        $response->assertRedirect('/');
    }

    public function testUserOrderDetailSuccess()
    {
        $this->demoUserLoginIn();
        $product = factory(Product::class)->create();
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
        $order = factory(Order::class)->create();
        $response = $this->call('GET', '/orders/1/detail');
        $this->assertEquals(200, $response->status());
    }

    public function testUserOrderDetailFaild()
    {
        $response = $this->call('GET', '/orders/999/detail');
        $this->assertEquals(404, $response->status());
        $response->assertSee('Not Found');
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
        $product = factory(Product::class)->create();
        $response = $this->call('GET', '/products/1');
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
        $response = $this->call('PATCH', '/profile/1', [
            'name' => 'test123',
            'email' => 'test@test.com'
        ]);
        $this->assertEquals(302, $response->status());
        $response->assertRedirect('/profile');
    }

    public function testUpdateFaild()
    {
        $response = $this->call('PATCH', '/profile/999', [
            'name' => 'test123',
            'email' => 'test@test.com'
        ]);
        $this->assertEquals(404, $response->status());
    }

    public function testIndexSuccess()
    {
        $this->demoAdminLoginIn();
        $response = $this->call('GET', '/users');
        $this->assertEquals(200, $response->status());
    }

    public function testIndexFaild()
    {
        $this->demoUserLoginIn();
        $response = $this->call('GET', '/users');
        $this->assertEquals(302, $response->status());
        $response->assertRedirect('/');
    }

    public function testDestroySuccess()
    {
        $this->demoAdminLoginIn();
        $response = $this->call('DELETE', '/users/1');
        $this->assertEquals(302, $response->status());
        $response->assertRedirect('/users');
    }

    public function testDestroyFaild()
    {
        $response = $this->call('DELETE', '/users/999');
        $this->assertEquals(404, $response->status());
    }
}
