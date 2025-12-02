

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\AppointmentController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin portal (only for management)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/admin/medewerkers', [AdminController::class, 'medewerkers'])->name('admin.medewerkers');
    Route::get('/admin/medewerkers/create', [AdminController::class, 'create'])->name('admin.medewerkers.create');
    Route::post('/admin/medewerkers', [AdminController::class, 'store'])->name('admin.medewerkers.store');
    Route::post('/admin/user/{id}/role', [AdminController::class, 'updateRole'])->name('admin.user.role');
    Route::get('/admin/user/{id}/availabilities', [AdminController::class, 'showAvailabilities'])->name('admin.user.availabilities');
    Route::get('/admin/user/{id}/edit', [AdminController::class, 'edit'])->name('admin.user.edit');
    Route::patch('/admin/user/{id}', [AdminController::class, 'update'])->name('admin.user.update');
    Route::delete('/admin/user/{id}', [AdminController::class, 'destroy'])->name('admin.user.destroy');
});

use App\Http\Controllers\AvailabilityNewController;
use App\Http\Controllers\InvoiceController;
Route::middleware('auth')->group(function () {
        // New per-date availability management (AJAX for calendar)
        Route::get('/availability-new', [AvailabilityNewController::class, 'index'])->name('availability.new.index');
        Route::post('/availability-new', [AvailabilityNewController::class, 'store'])->name('availability.new.store');
        Route::delete('/availability-new/{id}', [AvailabilityNewController::class, 'destroy'])->name('availability.new.destroy');
        Route::get('/availability-new/all', [AvailabilityNewController::class, 'all'])->name('availability.new.all');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Patient: book appointment with tandarts
    Route::get('/appointments/create/{tandarts}', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    // Patient: view own appointments
    Route::get('/appointments/my', [AppointmentController::class, 'myAppointments'])->name('appointments.my');

    // Tandarts: view own agenda
    Route::get('/appointments/tandarts', [AppointmentController::class, 'tandartsAgenda'])->name('appointments.tandarts');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Medewerker availability management
    Route::get('/availability', [AvailabilityController::class, 'index'])->name('availability.index');
    
    // Invoice routes
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{id}/download', [InvoiceController::class, 'download'])->name('invoices.download');
    
    // Management: view all invoices and delete
    Route::get('/admin/invoices', [InvoiceController::class, 'all'])->name('invoices.all');
    Route::delete('/admin/invoices/{id}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
});

require __DIR__.'/auth.php';
