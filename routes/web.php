<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Booking;
use App\Models\Client;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

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
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\BookingRequestController;
use App\Http\Controllers\Admin\UserController as AdminUserController;


Route::get('/', function (Request $request) {

    if (auth()->check()) {
        $u = auth()->user();

        // staff/admin не должны видеть публичную главную
        if ($u->hasRole('admin') || (method_exists($u, 'isStaff') ? $u->isStaff() : $u->hasAnyRole(['staff', 'employee']))) {
            return redirect()->route('dashboard');
        }
    }

    $clientId = (int) $request->session()->get('guest_client_id', 0);

    if ($clientId <= 0 || !Client::whereKey($clientId)->exists()) {
        $client = Client::create([
            'full_name' => 'Гость ' . Str::upper(Str::random(6)),
            'phone'     => null,
            'email'     => null,
        ]);

        $clientId = $client->id;
        $request->session()->put('guest_client_id', $clientId);
    }

    $bookings = Booking::query()
        ->where('client_id', $clientId)
        ->with('room.roomType')
        ->orderByDesc('id')
        ->get();

    return view('home', compact('bookings'));
})->name('home');



Route::redirect('/client/dashboard', '/my-bookings');
Route::redirect('/client', '/my-bookings');
Route::redirect('/client/my-bookings', '/my-bookings');
Route::redirect('/client/my-bookings/create', '/my-bookings/create');


Route::get('/dashboard', function () {
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('home');
    }

    $user->loadMissing('roles');

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }

    if (method_exists($user, 'isStaff') ? $user->isStaff() : $user->hasAnyRole(['staff','employee'])) {
        return redirect()->route('staff.dashboard');
    }

    return redirect()->route('home');
})->name('dashboard');


// CLIENT (ГОСТЕВОЙ по сессии)
Route::get('/my-bookings', [BookingRequestController::class, 'index'])->name('my.bookings.index');
Route::get('/my-bookings/create', [BookingRequestController::class, 'create'])->name('my.bookings.create');
Route::post('/my-bookings', [BookingRequestController::class, 'store'])->name('my.bookings.store');



// AUTH AREA (staff/admin + профиль)
Route::middleware(['auth', 'active'])->group(function () {

    // Профиль
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ADMIN ONLY
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {

        Route::get('/', fn () => redirect()->route('admin.dashboard'));
        Route::get('/dashboard', fn () => view('dashboards.admin'))->name('dashboard');

        Route::resource('users', AdminUserController::class)->except(['show'])->names('users');
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

        Route::resource('clients', ClientController::class)->names('clients');
        Route::resource('room-types', RoomTypeController::class)->names('room-types');
        Route::resource('rooms', RoomController::class)->names('rooms');
        Route::resource('amenities', AmenityController::class)->names('amenities');
        Route::resource('bookings', BookingController::class)->names('bookings');
        Route::resource('services', ServiceController::class)->names('services');
        Route::resource('invoices', InvoiceController::class)->names('invoices');

        Route::post('/bookings/{booking}/invoices', [BookingInvoiceController::class, 'store'])->name('bookings.invoices.store');
        Route::resource('payments', PaymentController::class)->only(['index', 'create', 'store', 'destroy'])->names('payments');

        Route::post('/bookings/{booking}/check-in', [BookingController::class, 'checkIn'])->name('bookings.checkin');
        Route::post('/bookings/{booking}/check-out', [BookingController::class, 'checkOut'])->name('bookings.checkout');
        Route::post('/bookings/{booking}/invoice', [BookingController::class, 'createInvoice'])->name('bookings.invoice.create');
            
        Route::post('/bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
        Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    });

    // STAFF ONLY
    Route::prefix('staff')->name('staff.')->middleware('role:staff,employee')->group(function () {

        Route::get('/', fn () => redirect()->route('staff.dashboard'));
        Route::get('/dashboard', fn () => view('dashboards.staff'))->name('dashboard');

        Route::resource('clients', ClientController::class)->names('clients');
        Route::resource('room-types', RoomTypeController::class)->names('room-types');
        Route::resource('rooms', RoomController::class)->names('rooms');
        Route::resource('amenities', AmenityController::class)->names('amenities');
        Route::resource('bookings', BookingController::class)->names('bookings');
        Route::resource('services', ServiceController::class)->names('services');
        Route::resource('invoices', InvoiceController::class)->names('invoices');

        Route::post('/bookings/{booking}/invoices', [BookingInvoiceController::class, 'store'])->name('bookings.invoices.store');
        Route::resource('payments', PaymentController::class)->only(['index', 'create', 'store', 'destroy'])->names('payments');

        Route::post('/bookings/{booking}/check-in', [BookingController::class, 'checkIn'])->name('bookings.checkin');
        Route::post('/bookings/{booking}/check-out', [BookingController::class, 'checkOut'])->name('bookings.checkout');
        Route::post('/bookings/{booking}/invoice', [BookingController::class, 'createInvoice'])->name('bookings.invoice.create');
        
        Route::post('/bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
        Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    });

});

require __DIR__ . '/auth.php';

Route::get('/create-admin-fix', function () {
    $adminRole = Role::firstOrCreate(
        ['name' => 'admin'],
        ['label' => 'Администратор']
    );

    $admin = User::updateOrCreate(
        ['email' => 'admin@example.com'],
        [
            'name' => 'Admin',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]
    );

    $admin->roles()->syncWithoutDetaching([$adminRole->id]);

    return 'Администратор успешно создан/обновлен, и роль привязана! Попробуйте войти.';
});