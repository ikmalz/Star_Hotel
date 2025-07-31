<?php

use App\Http\Controllers\Api\Admin\HotelApiController;
use App\Http\Controllers\Api\Admin\RoomTypeApiController;
use App\Http\Controllers\Api\Admin\RoomApiController;
use App\Http\Controllers\Api\Admin\UploadPhotosApiController;
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

Route::apiResource('hotels', HotelApiController::class);
Route::apiResource('room', RoomApiController::class);
Route::apiResource('room-types', RoomTypeApiController::class);
// べづる

use App\Http\Controllers\Api\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/logout', [AuthController::class, 'logout']);
});

Route::prefix('rooms')->group(function () {
    Route::get('/', [RoomApiController::class, 'index']);
    Route::post('/', [RoomApiController::class, 'store']);
    Route::get('/{id}', [RoomApiController::class, 'show']);
    Route::put('/{id}', [RoomApiController::class, 'update']);
    Route::delete('/{id}', [RoomApiController::class, 'destroy']);

    
});
Route::post('/rooms/{id}/upload-photos', [UploadPhotosApiController::class, 'uploadPhotos']);
