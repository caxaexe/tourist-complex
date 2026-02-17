<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AmenityController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\BookingInvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ReportController;

use App\Http\Controllers\BookingRequestController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'active'])->group(function () {

    // Профиль
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ADMIN ONLY
    Route::prefix('admin')->middleware('role:admin')->group(function () {

        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        // Управление персоналом (CRUD)
        Route::resource('users', AdminUserController::class)
            ->names('admin.users');

        // Журнал действий (только admin)
        Route::get('/audit-logs', [AuditLogController::class, 'index'])
            ->name('admin.audit-logs.index');
    });

    // EMPLOYEE + ADMIN (рабочая часть)
    Route::middleware('role:admin,employee')->group(function () {

        Route::get('/staff', fn () => 'Staff area')->name('staff');

        Route::resource('clients', ClientController::class);
        Route::resource('room-types', RoomTypeController::class);
        Route::resource('rooms', RoomController::class);
        Route::resource('amenities', AmenityController::class);
        Route::resource('bookings', BookingController::class);
        Route::resource('services', ServiceController::class);

        Route::resource('invoices', InvoiceController::class);

        Route::post('/bookings/{booking}/invoices', [BookingInvoiceController::class, 'store'])
            ->name('bookings.invoices.store');

        Route::resource('payments', PaymentController::class)
            ->only(['index', 'create', 'store', 'destroy']);

        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports.index');
    });

    // USER (обычный пользователь: заявки)
    Route::middleware('role:user')->group(function () {
        Route::get('/my-bookings', [BookingRequestController::class, 'index'])
            ->name('my.bookings.index');

        Route::get('/my-bookings/create', [BookingRequestController::class, 'create'])
            ->name('my.bookings.create');

        Route::post('/my-bookings', [BookingRequestController::class, 'store'])
            ->name('my.bookings.store');
    });
});

require __DIR__.'/auth.php';
