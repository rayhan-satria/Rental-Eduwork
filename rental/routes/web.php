<?php
use App\Http\Controllers\Admin\MotorcycleController;
use App\Http\Controllers\Admin\MotorcycleUnitController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===== Admin: kelola armada motor =====
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('motorcycles', MotorcycleController::class)->except('show');
        Route::resource('motorcycle-units', MotorcycleUnitController::class)->except('show');
});
require __DIR__.'/auth.php';
