<?php

use App\Http\Controllers\HotelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
;

Route::middleware('auth')->get('/users', [UserController::class, 'index'])->name('users.index');


Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::resource('hotels', HotelController::class);

    Route::get('/hotels/{hotel}/room-types', [RoomTypeController::class, 'index'])->name('room-types.index');
    Route::post('/room-types', [RoomTypeController::class, 'store'])->name('room-types.store');
    Route::post('/room-types/{roomType}/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::delete('/room-types/{id}', [RoomTypeController::class, 'destroy'])->name('room-types.destroy');
    Route::put('/room-types/{id}', [RoomTypeController::class, 'update'])->name('room-types.update');


    // Routes untuk Room Management
    Route::get('/room-types/{roomTypeId}/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/{id}', [RoomController::class, 'show'])->name('rooms.show');
    Route::put('/rooms/{id}', [RoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/{id}', [RoomController::class, 'destroy'])->name('rooms.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__ . '/auth.php';
