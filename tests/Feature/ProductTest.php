<?php

namespace Tests\Feature;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
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

    /**
     * 測試進入到首頁頁面
     *
     * @return void
     */
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
        $response->assertRedirect('/');
    }

    /**
     * 測試admin儲存商品
     *
     * @return void
     */
    public function testStoreSuccess()
    {
        $this->demoAdminLoginIn();
        $product = factory(Product::class)->create();
        $response = $this->call('POST', '/products', [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $product->quantity,
            'category' => $product->category,
            'origin_price' => $product->origin_price,
            'unit' => $product->unit,
            'description' => $product->description,
            'content' => $product->content,
            'image' => UploadedFile::fake()->image('photo.jpeg', 500, 500)->size(1000),
        ]);
        $this->assertEquals(302, $response->status());
        $response->assertRedirect('/products');

        $product = factory(Product::class)->create();
        $response = $this->call('POST', '/products', [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $product->quantity,
            'category' => $product->category,
            'origin_price' => $product->origin_price,
            'unit' => $product->unit,
            'description' => $product->description,
            'content' => $product->content,
            'imageUrl' =>
            'https://104-aws-training-cicd-bucket.s3-ap-northeast-1.amazonaws.com/natz/images/noimage.jpeg',
        ]);
        $this->assertEquals(302, $response->status());
        $response->assertRedirect('/products');
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

    /**
     * 測試admin更新商品
     *
     * @return void
     */
    public function testUpdateSuccess()
    {
        $this->demoAdminLoginIn();
        $product = factory(Product::class)->create();
        $response = $this->call('PATCH', '/products/1', [
            'name' => 'testStoreSuccess',
            'price' => '321',
            'quantity' => '321',
            'category' => 'testStore',
            'origin_price' => '123',
            'unit' => '個',
            'image' => UploadedFile::fake()->image('photo.jpeg', 500, 500)->size(1000),
        ]);
        $this->assertEquals(302, $response->status());
        $response->assertRedirect('/products');

        $product = factory(Product::class)->create();
        $response = $this->call('PATCH', '/products/1', [
            'name' => 'testStoreSuccess',
            'price' => '321',
            'quantity' => '321',
            'category' => 'testStore',
            'origin_price' => '123',
            'unit' => '個',
            'imageUrl' =>
            'https://104-aws-training-cicd-bucket.s3-ap-northeast-1.amazonaws.com/natz/images/noimage.jpeg',
        ]);
        $this->assertEquals(302, $response->status());
        $response->assertRedirect('/products');
    }

    public function testUpdateFaild()
    {
        $this->demoAdminLoginIn();
        $product = factory(Product::class)->create();

        $response = $this->call('PATCH', '/products/3', [
            'name' => 'testStoreFaild',
            'quantity' => '321',
            'category' => 'testStore',
            'origin_price' => '123',
            'unit' => '個',
        ]);
        $this->assertEquals(422, $response->status());

        $response = $this->call('PATCH', '/products/3', [
            'name' => 'testStoreFaild',
            'price' => '123',
            'quantity' => '321',
            'category' => 'testStore',
            'origin_price' => '123',
            'unit' => '個',
        ]);
        $this->assertEquals(404, $response->status());
    }

    /**
     * 測試admin刪除商品
     *
     * @return void
     */
    public function testDestroySuccess()
    {
        $this->demoAdminLoginIn();
        $product = factory(Product::class)->create();
        $this->call('POST', '/products', [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $product->quantity,
            'category' => $product->category,
            'origin_price' => $product->origin_price,
            'unit' => $product->unit,
            'description' => $product->description,
            'content' => $product->content,
            'image' => UploadedFile::fake()->image('photo.jpeg', 500, 500)->size(1000),
        ]);
        $response = $this->call('DELETE', '/products/2');
        $this->assertEquals(302, $response->status());
        $response->assertRedirect('/products');
    }

    public function testDestroyFaild()
    {
        $this->demoAdminLoginIn();
        $response = $this->call('DELETE', '/products/999');
        $this->assertEquals(404, $response->status());
    }
}
