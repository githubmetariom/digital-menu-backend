<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Financial\app\Http\Controllers\Api\V1\DiscountCodeController;
use Modules\Financial\app\Http\Controllers\Api\V1\OrderController;
use Modules\Financial\app\Http\Controllers\Api\V1\PaymentController;
use Modules\Financial\app\Http\Controllers\Api\V1\TransactionController;

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
Route::middleware(['auth:sanctum'])->prefix('order/v1')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::post('/', [OrderController::class, 'create']);
    Route::get('/{order}', [OrderController::class, 'show']);
    Route::get('/invoice/{order}', [OrderController::class, 'invoices']);
    Route::put('/{order}', [OrderController::class, 'update']);
//    Route::delete('/{order}', [OrderController::class, 'destroy']);
});

Route::middleware(['auth:sanctum'])->prefix('discount/v1')->group(function () {
    Route::get('/', [DiscountCodeController::class, 'index']);
    Route::post('/', [DiscountCodeController::class, 'create']);
    Route::get('/{discount_code}', [DiscountCodeController::class, 'show']);
});

Route::middleware(['auth:sanctum'])->prefix('invoice/v1')->group(function () {
    Route::get('/', [DiscountCodeController::class, 'index']);
    Route::post('/', [\Modules\Financial\app\Http\Controllers\Api\V1\InvoiceController::class, 'create']);
    Route::get('/{discount_code}', [DiscountCodeController::class, 'show']);
});

Route::middleware(['auth:sanctum'])->prefix('transaction/v1')->group(function () {
    Route::get('/', [TransactionController::class, 'index']);
    Route::post('/payment/', [PaymentController::class, 'payment']);
    Route::get('/user', [TransactionController::class, 'userTransactions']);
});
