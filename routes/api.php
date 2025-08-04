<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\BookingController;
use App\Http\Controllers\Api\Admin\HotelApiController;
use App\Http\Controllers\Api\Admin\RoomApiController;
use App\Http\Controllers\Api\Admin\RoomTypeApiController;
use App\Http\Controllers\Api\Admin\RoomFacilityApiController;
use App\Http\Controllers\Api\Admin\UploadPhotosApiController;
use App\Http\Controllers\Api\RoomFacilityController;
use App\Http\Controllers\Api\Admin\PaymentAPIController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/admin/login', [AuthController::class, 'loginAdmin']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('user')->group(function () {
        Route::get('/room-facilities', [RoomFacilityController::class, 'index']);
        Route::get('/room-facilities/{room_type_id}', [RoomFacilityController::class, 'show']);

        Route::apiResource('bookings', BookingController::class);
        Route::post('payments', [PaymentAPIController::class, 'store']);
        Route::get('payments/{id}', [PaymentAPIController::class, 'show']);
    });


    Route::prefix('admin')->group(function () {
        Route::get('/room-facilities', [RoomFacilityApiController::class, 'index']);
        Route::post('/room-facilities', [RoomFacilityApiController::class, 'store']);
        Route::put('/room-facilities/{id}', [RoomFacilityApiController::class, 'update']);
        Route::delete('/room-facilities/{id}', [RoomFacilityApiController::class, 'destroy']);

        Route::apiResource('hotels', HotelApiController::class);
        Route::apiResource('rooms', RoomApiController::class);
        Route::apiResource('room-types', RoomTypeApiController::class);

        Route::post('/rooms/{id}/upload-photos', [UploadPhotosApiController::class, 'uploadPhotos']);

        Route::apiResource('bookings', BookingController::class);

        Route::get('payments', [PaymentAPIController::class, 'index']);
        Route::get('payments/{id}', [PaymentAPIController::class, 'show']);
        Route::post('payments/{id}/verify', [PaymentAPIController::class, 'verify']);
        Route::delete('payments/{id}', [PaymentAPIController::class, 'destroy']);
    });
});
