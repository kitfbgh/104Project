<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $products = Product::simplePaginate(8);
    return view('welcome', compact('products'));
})->name('welcome');

$namespacePrefix = 'App\\Http\\Controllers\\';

Auth::routes();

Route::get('/dashboard', ['uses' => $namespacePrefix . 'DashboardController@index', 'as' => 'dashboard']);

Route::get('products/index', ['uses' => $namespacePrefix . 'ProductController@index', 'as' => 'products.index']);
Route::get('products', ['uses' => $namespacePrefix . 'ProductController@page', 'as' => 'products']);
Route::post('products', ['uses' => $namespacePrefix . 'ProductController@store', 'as' => 'products.create']);
Route::put('products/{productId}', ['uses' => $namespacePrefix . 'ProductController@update', 'as' => 'products.update']);
Route::delete('products/{productId}', ['uses' => $namespacePrefix . 'ProductController@destroy', 'as' => 'products.delete']);

Route::get('cart', ['uses' => $namespacePrefix . 'CartController@index', 'as' => 'cart']);
Route::get('cart/add/{product}', ['uses' => $namespacePrefix . 'CartController@add', 'as' => 'cart.add']);
Route::get('cart/update/{productId}', ['uses' => $namespacePrefix . 'CartController@update', 'as' => 'cart.update']);
Route::get('cart/delete/{productId}', ['uses' => $namespacePrefix . 'CartController@destroy', 'as' => 'cart.delete']);

Route::get('orders', ['uses' => $namespacePrefix . 'OrderController@index', 'as' => 'orders']);
Route::get('orders/checkout', ['uses' => $namespacePrefix . 'OrderController@checkout', 'as' => 'orders.checkout']);
Route::post('orders', ['uses' => $namespacePrefix . 'OrderController@store', 'as' => 'orders.create']);
Route::put('orders/{orderId}', ['uses' => $namespacePrefix . 'OrderController@update', 'as' => 'orders.update']);
Route::delete('orders/{orderId}', ['uses' => $namespacePrefix . 'OrderController@destroy', 'as' => 'orders.delete']);

Route::get('orders/{userId}', ['uses' => $namespacePrefix . 'UserController@order', 'as' => 'user.orders']);
Route::get('profile', ['uses' => $namespacePrefix . 'UserController@profile', 'as' => 'user.profile']);
