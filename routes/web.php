<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // 🔹 ПРОФИЛЬ (оставляем как есть)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 🔹 НОВОЕ: Админ-панель
    Route::get('/admin', function () {
        return 'Admin area';
    })->middleware('role:admin');

    // 🔹 НОВОЕ: Панель сотрудника
    Route::get('/staff', function () {
        return 'Staff area';
    })->middleware('role:admin,employee');

});

require __DIR__.'/auth.php';
