<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\User\app\Http\Controllers\Api\V1\AddressController;
use Modules\User\app\Http\Controllers\Api\V1\UserController;
use Modules\User\app\Http\Controllers\NotificationController;

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

//Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
//    Route::get('user', fn(Request $request) => $request->user())->name('user');
//});


Route::group(['prefix' => 'users/v1'], function () {
    Route::middleware('throttle:otp-request')->post('/otp-request/', [UserController::class, 'otpCodeRequest']);
    Route::post('/signup/', [UserController::class, 'signup']);
    Route::post('/login/', [UserController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [UserController::class, 'userCreate']);
        Route::get('/referral/{user}', [UserController::class, 'referrals']);
        Route::get('/balance/', [UserController::class, 'getBalance']);
        Route::post('/{user}', [UserController::class, 'userUpdate']);
    });
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/notify/', [NotificationController::class, 'notify']);
});

Route::middleware(['auth:sanctum'])->prefix('address/v1')->group(function () {
    Route::get('/', [AddressController::class, 'index']);
    Route::post('/', [AddressController::class, 'create']);
    Route::get('/{address}', [AddressController::class, 'show']);
    Route::put('/{address}', [AddressController::class, 'update']);
    Route::delete('/{address}', [AddressController::class, 'destroy']);
});
