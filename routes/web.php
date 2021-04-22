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

$namespace = 'App\\Http\\Controllers\\';

Auth::routes(['verify' => true]);

Route::get('/dashboard', ['uses' => $namespace . 'DashboardController@index', 'as' => 'dashboard']);
Route::get('/users', ['uses' => $namespace . 'UserController@index', 'as' => 'users']);
Route::delete('/users/{userId}', ['uses' => $namespace . 'UserController@destroy', 'as' => 'users.delete']);

Route::get('products', ['uses' => $namespace . 'ProductController@page', 'as' => 'products']);
Route::post('products', ['uses' => $namespace . 'ProductController@store', 'as' => 'products.create']);
Route::patch('products/{productId}', ['uses' => $namespace . 'ProductController@update', 'as' => 'products.update']);
Route::delete('products/{productId}', ['uses' => $namespace . 'ProductController@destroy', 'as' => 'products.delete']);

Route::get('cart', ['uses' => $namespace . 'CartController@index', 'as' => 'cart']);
Route::get('cart/add/{product}', ['uses' => $namespace . 'CartController@add', 'as' => 'cart.add']);
Route::get('cart/update/{productId}', ['uses' => $namespace . 'CartController@update', 'as' => 'cart.update']);
Route::get('cart/delete/{productId}', ['uses' => $namespace . 'CartController@destroy', 'as' => 'cart.delete']);

Route::get('orders', ['uses' => $namespace . 'OrderController@index', 'as' => 'orders']);
Route::get('orders/order/{orderId}', ['uses' => $namespace . 'OrderController@orderDetail', 'as' => 'orders.detail']);
Route::get('orders/checkout', ['uses' => $namespace . 'OrderController@checkout', 'as' => 'orders.checkout']);
Route::post('orders', ['uses' => $namespace . 'OrderController@store', 'as' => 'orders.create']);
Route::patch('orders/{orderId}', ['uses' => $namespace . 'OrderController@update', 'as' => 'orders.update']);
Route::delete('orders/{orderId}', ['uses' => $namespace . 'OrderController@destroy', 'as' => 'orders.delete']);

Route::get('orders/user/{userId}', ['uses' => $namespace . 'UserController@order', 'as' => 'user.orders']);
Route::get(
    'orders/{orderId}/detail',
    [
        'uses' => $namespace . 'UserController@orderDetail',
        'as' => 'user.order.detail'
    ]
);
Route::get('products/{productId}', ['uses' => $namespace . 'UserController@productDetail', 'as' => 'products.detail']);
Route::get('profile', ['uses' => $namespace . 'UserController@profile', 'as' => 'user.profile']);
Route::patch('profile/{userId}', ['uses' => $namespace . 'UserController@update', 'as' => 'user.profile.update']);
