<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\BookingController;
use App\Http\Controllers\Api\Admin\CityApiController;
use App\Http\Controllers\Api\Admin\FloorApiController;
use App\Http\Controllers\Api\Admin\HotelApiController;
use App\Http\Controllers\Api\Admin\RoomApiController;
use App\Http\Controllers\Api\Admin\RoomTypeApiController;
use App\Http\Controllers\Api\Admin\RoomFacilityApiController;
use App\Http\Controllers\Api\Admin\UploadPhotosApiController;
use App\Http\Controllers\Api\RoomFacilityController;
use App\Http\Controllers\Api\Admin\PaymentAPIController;
use App\Http\Controllers\Api\Admin\ProvinceApiController;
use App\Http\Controllers\Api\Admin\RatingController;
use App\Http\Controllers\Api\User\UserBookingController;
use App\Http\Controllers\Api\User\UserPaymentController;
use App\Http\Controllers\Api\User\UserRatingController;
use App\Http\Controllers\Api\User\CommentController;

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

        Route::apiResource('bookings', UserBookingController::class);
        Route::post('bookings/{id}/checkout', [UserBookingController::class, 'checkout']);
        Route::post('bookings/{id}/cancel', [UserBookingController::class, 'rejectBooking']);

        Route::get('payments', [UserPaymentController::class, 'index']);
        Route::post('payments', [UserPaymentController::class, 'store']);
        Route::get('payments/{id}', [UserPaymentController::class, 'show']);
        Route::post('payments/{id}/request-refund', [UserPaymentController::class, 'requestRefund']);

        Route::get('ratings', [UserRatingController::class, 'index']);
        Route::get('ratings/{id}', [UserRatingController::class, 'show']);
        Route::post('ratings', [UserRatingController::class, 'store']);

         Route::post('/comments', [CommentController::class, 'store']);

    // Ambil daftar komentar berdasarkan tipe (hotel / room_type) dan ID
    Route::get('/comments/{type}/{id}', [CommentController::class, 'list']);
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

        Route::get('/provinces', [ProvinceApiController::class, 'index']);
        Route::post('/provinces', [ProvinceApiController::class, 'store']);
        Route::get('/provinces/{id}', [ProvinceApiController::class, 'show']);
        Route::put('/provinces/{id}', [ProvinceApiController::class, 'update']);
        Route::delete('/provinces/{id}', [ProvinceApiController::class, 'destroy']);
        Route::get('/provinces/{id}/cities', [ProvinceApiController::class, 'cities']);

        Route::get('/cities', [CityApiController::class, 'index']);
        Route::post('/cities', [CityApiController::class, 'store']);
        Route::get('/cities/{id}', [CityApiController::class, 'show']);
        Route::put('/cities/{id}', [CityApiController::class, 'update']);
        Route::delete('/cities/{id}', [CityApiController::class, 'destroy']);
        Route::get('/cities/{id}/hotels', [CityApiController::class, 'hotels']);

        Route::get('ratings', [RatingController::class, 'index']);
    });

    Route::prefix('floors')->group(function () {
        Route::get('/', [FloorApiController::class, 'index']);
        Route::post('/', [FloorApiController::class, 'store']);
        Route::get('/{id}', [FloorApiController::class, 'show']);
        Route::put('/{id}', [FloorApiController::class, 'update']);
        Route::delete('/{id}', [FloorApiController::class, 'destroy']);

        Route::get('/hotel/{hotelId}', [FloorApiController::class, 'getFloorsByHotel']);
    });
});
