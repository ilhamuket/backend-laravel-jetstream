<?php

use App\Http\Controllers\Api\EditUserController;
use App\Http\Controllers\API\ProductCategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\API\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('products', [ProductController::class, 'all']);

Route::get('categories', [ProductCategoryController::class, 'all']);

Route::post('register', App\Http\Controllers\API\UserController::class)->name('register');

Route::post('login', App\Http\Controllers\API\LoginController::class)->name('login');

Route::middleware('auth:api')->group(function () {
    Route::get('user', [UserController::class, 'fetch']);
    Route::post('user', [UserController::class, 'updateUser']);
    Route::post('logout', App\Http\Controllers\API\LogoutController::class)->name('logout');

    Route::get('transactions', [TransactionController::class, 'all']);
    Route::post('checkout', [TransactionController::class, 'checkout']);
});


// Route::post('edit', App\Http\Controllers\API\LoginController::class)->name('edit');