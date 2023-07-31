<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\ClientUserController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\VendorUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Admin routes
Route::group(['prefix' => 'admin'], function () {
    Route::post('login', [AdminController::class, 'login'])->name('login');;
    Route::post('logout', [AdminController::class, 'logout'])->middleware('auth:admin');
    Route::get('profile', [AdminController::class, 'getProfile'])->middleware('auth:admin');
    Route::post('forget-password', [AdminController::class, 'forgetPassword']);
});

// Vendor routes
Route::group(['prefix' => 'vendor'], function () {
    Route::post('login', [VendorUserController::class, 'login'])->name('login');;
    Route::post('logout', [VendorUserController::class, 'logout'])->middleware('auth:vendor');
    Route::get('profile', [VendorUserController::class, 'getProfile'])->middleware('auth:vendor');
    Route::post('forget-password', [VendorUserController::class, 'forgetPassword']);
    Route::post('register', [VendorUserController::class, 'register']);
});

// Client routes
Route::group(['prefix' => 'client'], function () {
    Route::post('login', [ClientUserController::class, 'login'])->name('login');;
    Route::post('logout', [ClientUserController::class, 'logout'])->middleware('auth:client');
    Route::get('profile', [ClientUserController::class, 'getProfile'])->middleware('auth:client');
    Route::post('forget-password', [ClientUserController::class, 'forgetPassword']);
    Route::post('register', [ClientUserController::class, 'register']);
});

// Product routes
Route::prefix('products')->group(function () {
    Route::apiResource('products', ProductController::class);
});
