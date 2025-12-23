<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PnsController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Route untuk PNS biasa
    Route::get('/pns', function () {
        return view('pns.index');
    })->name('pns.index');
    Route::get('/pns/tracking', [PnsController::class, 'tracking'])->name('pns.tracking');
    Route::get('/pns/tracking/{id}', [PnsController::class, 'detail'])->name('pns.tracking.detail');

    //Route untuk Admin OPD
    Route::get('/opd', function () {})->name('opd.index');
    Route::get('/opd/tracking', function () {})->name('opd.tracking');

    //Route untuk Super Admin
    Route::get('/bkpsdm', function () {})->name('admin.index');
    Route::get('/bkpsdm/tracking', function () {})->name('admin.tracking');
    Route::get('/bkpsdm/manage-users', function () {})->name('admin.manage-users');
    Route::get('/bkpsdm/manage-opd', function () {})->name('admin.manage-opd');
    Route::get('/bkpsdm/laporan', function () {})->name('admin.laporan');
    Route::get('/bkpsdm/settings', function () {})->name('admin.settings');
    Route::get('/bkpsdm/sk', function () {})->name('admin.sk');
});


require __DIR__.'/auth.php';
