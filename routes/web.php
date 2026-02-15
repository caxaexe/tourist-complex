<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AmenityController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Профиль
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Только admin
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->middleware(['auth', 'role:admin'])->name('admin.dashboard');

    // admin + employee
    Route::get('/staff', fn () => 'Staff area')->middleware('role:admin,employee');

    // CRUD клиентов (admin + employee)
    Route::resource('clients', ClientController::class)->middleware('role:admin,employee');

    // CRUD типов комнат
    Route::resource('room-types', RoomTypeController::class)
    ->middleware('role:admin,employee');

    // CRUD комнат
    Route::resource('rooms', RoomController::class)
    ->middleware('role:admin,employee');

    // CRUD удобств
    Route::resource('amenities', AmenityController::class)
    ->middleware('role:admin,employee');

    // CRUD бронирований
    Route::resource('bookings', BookingController::class)
    ->middleware('role:admin,employee');

    // CRUD сервисов
    Route::resource('services', ServiceController::class)
    ->middleware('role:admin,employee');



});

require __DIR__.'/auth.php';
