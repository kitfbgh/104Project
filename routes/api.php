<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']); //註冊
Route::post('/login', [AuthController::class, 'login']); //登入

Route::get('/products/all', [ProductApiController::class, 'index']);
Route::get('/products/{productId}', [ProductApiController::class, 'show']);

Route::middleware('auth:sanctum')->post('/products', [ProductApiController::class, 'store']);
Route::put('/products/{productId}', [ProductApiController::class, 'update']);
Route::delete('/products/{productId}', [ProductApiController::class, 'destroy']);
