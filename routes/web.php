<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware('guest')->get('/login', function () {
    return view('auth.login'); 
})->name('login');

Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } 
    
    return redirect()->route('guests.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::post('/users', [AdminController::class, 'store'])->name('users.store');
    Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');
    Route::post('/cleanup-photos', [AdminController::class, 'cleanupPhotos'])->name('cleanup_photos');
    Route::post('/delete-selected-photos', [AdminController::class, 'deleteSelectedPhotos'])->name('delete_selected_photos');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/api/guests/search', [GuestController::class, 'ajaxSearch'])->name('guests.ajax_search');

    // Rute Hapus Masal Riwayat (Halaman Souvenir)
    Route::delete('/guests/logs/bulk', [GuestController::class, 'bulkDestroy'])->name('guests.logs.bulk_destroy');

    // Rute Hapus Masal Tamu (Halaman List Tamu) - INI YANG DITAMBAHKAN
    Route::delete('/guests/bulk-delete', [GuestController::class, 'bulkDestroyGuests'])->name('guests.bulk_destroy');

    Route::get('/guests-export', [GuestController::class, 'export'])->name('guests.export');
    Route::post('/guests-import', [GuestController::class, 'import'])->name('guests.import');
    Route::post('/guests/bulk-qr', [GuestController::class, 'exportQrPdf'])->name('guests.bulk_qr');

    Route::put('/guests/logs/update', [GuestController::class, 'updateLog'])->name('guests.logs.update');
    Route::delete('/guests/logs/destroy', [GuestController::class, 'destroyLog'])->name('guests.logs.destroy');

    Route::resource('guests', GuestController::class);
    // Route bulk_qr sudah didefinisikan di baris atas (guests.bulk_qr)
    Route::get('/server-1', [GuestController::class, 'server1'])->name('server1');
    Route::post('/server-1/checkin', [GuestController::class, 'processCheckinServer1'])->name('server1.checkin');

    Route::post('/guests/upload-photo', [GuestController::class, 'uploadPhoto'])->name('guests.upload_photo');
    Route::get('/souvenir', [GuestController::class, 'souvenir'])->name('souvenir');
    Route::get('/guests/{id}/history', [GuestController::class, 'getHistory'])->name('guests.history');

    Route::get('/tamu-hadir', [GuestController::class, 'attendance'])->name('attendance');
    Route::get('/tamu-hadir/pdf', [GuestController::class, 'exportPdf'])->name('attendance.pdf');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';