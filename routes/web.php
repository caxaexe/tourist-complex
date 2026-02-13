<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomTypeController;


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
    Route::get('/admin', fn () => 'Admin area')->middleware('role:admin');

    // admin + employee
    Route::get('/staff', fn () => 'Staff area')->middleware('role:admin,employee');

    // CRUD клиентов (admin + employee)
    Route::resource('clients', ClientController::class)->middleware('role:admin,employee');

    // CRUD типов комнат
    Route::resource('room-types', RoomTypeController::class)
    ->middleware('role:admin,employee');

});

require __DIR__.'/auth.php';
