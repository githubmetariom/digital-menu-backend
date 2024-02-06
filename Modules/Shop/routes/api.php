<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Shop\app\Http\Controllers\Api\V1\CategoryController;
use Modules\Shop\app\Http\Controllers\Api\V1\FoodController;
use Modules\Shop\app\Http\Controllers\Api\V1\StoreController;

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

Route::middleware(['auth:sanctum'])->prefix('store/v1')->group(function () {
    Route::get('/', [StoreController::class, 'index']);
    Route::post('/', [StoreController::class, 'create']);
    Route::get('/language/', [StoreController::class, 'getStoreByLanguage']);
    Route::get('/{store}', [StoreController::class, 'show']);
    Route::get('/order/{store}', [StoreController::class, 'orders']);
    Route::put('/{store}', [StoreController::class, 'update']);
    Route::delete('/{store}', [StoreController::class, 'destroy']);
});

Route::middleware(['auth:sanctum'])->prefix('category/v1')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/', [CategoryController::class, 'create']);
    Route::get('/language/', [CategoryController::class, 'getCategoryByLanguage']);
    Route::get('/{store}', [CategoryController::class, 'show']);
    Route::put('/{store}', [CategoryController::class, 'update']);
    Route::delete('/{store}', [CategoryController::class, 'destroy']);
});

Route::middleware(['auth:sanctum'])->prefix('food/v1')->group(function () {
    Route::get('/', [FoodController::class, 'index']);
    Route::post('/', [FoodController::class, 'create']);
    Route::get('/language/', [FoodController::class, 'getFoodByLanguage']);
    Route::get('/{food}', [FoodController::class, 'show']);
    Route::put('/{food}', [FoodController::class, 'update']);
    Route::delete('/{food}', [FoodController::class, 'destroy']);
});
