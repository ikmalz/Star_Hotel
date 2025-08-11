<?php

use App\Http\Controllers\HotelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomFacilityController;
use App\Http\Controllers\RoomPhotoController;
use App\Http\Controllers\RoomTypeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookingWebController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\PaymentWebController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\RatingWebController;
use App\Http\Controllers\CommentWebController;
    /*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/;

Route::middleware('auth')->get('/users', [UserController::class, 'index'])->name('users.index');


Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');




Route::middleware('auth')->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('comments', CommentWebController::class)->only(['index', 'show', 'destroy']);
    });
});

Route::middleware('auth')->group(function () {
    Route::resource('hotels', HotelController::class);

    Route::resource('room_facilities', RoomFacilityController::class);

    Route::get('/hotels/{hotel}/room-types', [RoomTypeController::class, 'index'])->name('room-types.index');
    Route::post('/room-types', [RoomTypeController::class, 'store'])->name('room-types.store');
    Route::post('/room-types/{roomType}/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::delete('/room-types/{id}', [RoomTypeController::class, 'destroy'])->name('room-types.destroy');
    Route::put('/room-types/{id}', [RoomTypeController::class, 'update'])->name('room-types.update');
    Route::delete('/room-photos/delete/{photo}', [RoomPhotoController::class, 'deletePhoto'])->name('room-photos.delete');

    Route::get('/room-types/{roomTypeId}/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/{id}', [RoomController::class, 'show'])->name('rooms.show');
    Route::put('/rooms/{id}', [RoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/{id}', [RoomController::class, 'destroy'])->name('rooms.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/bookings', [BookingWebController::class, 'index'])->name('bookings.index');
    Route::patch('/bookings/{id}/status', [BookingWebController::class, 'updateStatus'])->name('bookings.updateStatus');

    Route::resource('bookings', BookingWebController::class);

    Route::get('payments', [PaymentWebController::class, 'index'])->name('payments.index');
    Route::get('payments/{id}', [PaymentWebController::class, 'show'])->name('payments.show');
    Route::post('payments/{id}/verify', [PaymentWebController::class, 'verify'])->name('payments.verify');
    Route::delete('payments/{id}', [PaymentWebController::class, 'destroy'])->name('payments.destroy');
    Route::patch('/admin/payments/{payment}/refund', [PaymentWebController::class, 'refund'])->name('admin.payments.refund');

    Route::get('/admin/payments/{id}', [PaymentWebController::class, 'show'])->name('payments.show');
    Route::patch('/admin/payments/{id}/verify', [PaymentWebController::class, 'verify'])->name('payments.verify');

    Route::resource('provinces', ProvinceController::class);
    Route::resource('cities', CityController::class);

    Route::get('/ratings', [RatingWebController::class, 'index'])->name('ratings.index');

    Route::prefix('floors')->group(function () {
        Route::get('/floors', [FloorController::class, 'all'])->name('floors.all');
        Route::get('/{roomType}', [FloorController::class, 'index'])->name('floors.index');
        Route::post('/{roomType}', [FloorController::class, 'store'])->name('floors.store');
        Route::delete('/delete/{id}', [FloorController::class, 'destroy'])->name('floors.destroy');
    });
});

require __DIR__ . '/auth.php';
