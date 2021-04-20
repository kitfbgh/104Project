<?php

namespace Tests\Feature;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ProductTest extends TestCase
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

    public function testPageSuccess()
    {
        $this->demoAdminLoginIn();
        $response = $this->call('GET', '/products');
        $this->assertEquals(200, $response->status());
    }

    public function testPageFaild()
    {
        $this->demoUserLoginIn();
        $response = $this->call('GET', '/products');
        $this->assertEquals(302, $response->status());
    }

    public function testStoreSuccess()
    {
        $this->demoAdminLoginIn();
        $response = $this->call('POST', '/products', [
            'name' => 'testStore',
            'price' => '123',
            'quantity' => '123',
            'category' => 'testStore',
            'origin_price' => '123',
            'unit' => '個',
        ]);
        $this->assertEquals(302, $response->status());
    }

    public function testStoreFaild()
    {
        $this->demoAdminLoginIn();
        $response = $this->call('POST', '/products', [
            'name' => 'testStore',
            'price' => 123,
            'quantity' => '123',
            'category' => 'testStore',
            'origin_price' => '123',
            'unit' => '個',
        ]);
        $this->assertEquals(422, $response->status());
    }

    public function testUpdateSuccess()
    {
        $this->demoAdminLoginIn();
        $product = Product::create([
            'name' => 'testStoreSuccess',
            'price' => '123',
            'quantity' => '123',
            'category' => 'testStore',
            'origin_price' => '123',
            'unit' => '個',
        ]);

        $response = $this->call('PATCH', '/products/1', [
            'name' => 'testStoreSuccess',
            'price' => '321',
            'quantity' => '321',
            'category' => 'testStore',
            'origin_price' => '123',
            'unit' => '個',
        ]);
        $this->assertEquals(302, $response->status());
    }

    public function testUpdateFaild()
    {
        $this->demoAdminLoginIn();
        $product = Product::create([
            'name' => 'testStoreFaild',
            'price' => '123',
            'quantity' => '123',
            'category' => 'testStore',
            'origin_price' => '123',
            'unit' => '個',
        ]);

        $response = $this->call('PATCH', '/products/3', [
            'name' => 'testStoreFaild',
            'quantity' => '321',
            'category' => 'testStore',
            'origin_price' => '123',
            'unit' => '個',
        ]);
        $this->assertEquals(422, $response->status());
    }

    public function testDestroySuccess()
    {
        $this->demoAdminLoginIn();
        $product = Product::create([
            'name' => 'testDestroySuccess',
            'price' => '123',
            'quantity' => '123',
            'category' => 'testStore',
            'origin_price' => '123',
            'unit' => '個',
        ]);
        $response = $this->call('DELETE', '/products/1');
        $this->assertEquals(302, $response->status());
    }

    public function testDestroyFaild()
    {
        $this->demoAdminLoginIn();
        $response = $this->call('DELETE', '/products/999');
        $this->assertEquals(500, $response->status());
    }
}
