<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PnsController;
use Illuminate\Support\Facades\Route;


Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified']); // Alias if needed

// Rute untuk menguji koneksi BKN / SIASN Data Utama
Route::get('/test-siasn/{nip}', function($nip) {
    try {
        // Membersihkan cache konfigurasi agar selalu mengambil nilai terbaru dari file .env (tanpa harus restart server berulang-kali)
        \Illuminate\Support\Facades\Artisan::call('config:clear');

        $siasnService = new \App\Services\SiasnApiService();
        $response = $siasnService->getDataUtama($nip);
        
        return response()->json([
            'status' => 'success',
            'data' => $response
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

// Rute untuk endpoint Select2 Referensi UNOR
Route::get('/test-siasn/referensi/unor', function(\Illuminate\Http\Request $request) {
    try {
        // Cache hasil respons API Siasn selama 24 jam untuk pencarian referensi UNOR agar super cepat (menghindari request ke BKN terus menerus)
        $response = \Illuminate\Support\Facades\Cache::remember('siasn_referensi_unor_data', 86400, function () {
            $siasnService = new \App\Services\SiasnApiService();
            return $siasnService->getReferensiUnor();
        });

        $search = $request->query('q');
        $data = $response['data'] ?? [];

        if ($search) {
            $data = array_filter($data, function($item) use ($search) {
                // Biasanya nama properti adalah NamaUnor atau nama
                $name = $item['NamaUnor'] ?? $item['nama'] ?? $item['namaUnor'] ?? '';
                return stripos($name, $search) !== false;
            });
        }

        // Limit data agar browser tidak hang jika dirender semua (biasanya Select2 cukup 50)
        $data = array_slice(array_values($data), 0, 50);

        // Map data agar sesuai dengan format { id, text } milik Select2
        $results = array_map(function($item) {
            return [
                'id' => $item['Id'] ?? $item['id'] ?? '',
                'text' => $item['NamaUnor'] ?? $item['nama'] ?? $item['namaUnor'] ?? 'Unknown'
            ];
        }, $data);

        return response()->json([
            'results' => $results
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'results' => [],
            'error' => $e->getMessage()
        ]);
    }
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route untuk PNS biasa (Role 3)
    Route::middleware('role:3')->group(function () {
        Route::get('/pns', [\App\Http\Controllers\PnsController::class, 'index'])->name('pns.index');
        Route::get('/pns/usulan/create', [\App\Http\Controllers\Pns\AjukanMutasiController::class, 'create'])->name('pns.usulan.create');
        Route::post('/pns/usulan/upload-temp', [\App\Http\Controllers\Pns\AjukanMutasiController::class, 'uploadTemp'])->name('pns.usulan.uploadTemp');
        Route::post('/pns/usulan', [\App\Http\Controllers\Pns\AjukanMutasiController::class, 'store'])->name('pns.usulan.store');
        Route::post('/pns/usulan/revisi/{id_berkas}', [\App\Http\Controllers\Pns\AjukanMutasiController::class, 'revisiBerkas'])->name('pns.usulan.revisi');
        Route::get('/pns/tracking', [\App\Http\Controllers\PnsController::class, 'tracking'])->name('pns.tracking');
        Route::get('/pns/tracking/{id}', [\App\Http\Controllers\PnsController::class, 'detail'])->name('pns.tracking.detail');
    });

    // Route untuk Pimpinan (Kepala BKPSDM - Role 0)
    Route::middleware('role:0')->group(function () {
        Route::get('/pimpinan/inbox', [\App\Http\Controllers\PimpinanController::class, 'inbox'])->name('pimpinan.inbox');
        Route::get('/pimpinan/riwayat', [\App\Http\Controllers\PimpinanController::class, 'riwayat'])->name('pimpinan.riwayat');
        Route::post('/pimpinan/disposisi/{id}', [\App\Http\Controllers\PimpinanController::class, 'storeDisposisi'])->name('pimpinan.disposisi.store');
        // Pimpinan juga bisa melihat detail tracking pns
        Route::get('/pimpinan/tracking/{id}', [\App\Http\Controllers\PnsController::class, 'detail'])->name('pimpinan.tracking.detail');
    });

    // Route untuk Kepala Bidang (Kabid - Role 4)
    Route::middleware('role:4')->group(function () {
        Route::get('/kabid/inbox', [\App\Http\Controllers\KabidController::class, 'inbox'])->name('kabid.inbox');
        Route::get('/kabid/riwayat', [\App\Http\Controllers\KabidController::class, 'riwayat'])->name('kabid.riwayat');
        Route::post('/kabid/disposisi/{id}', [\App\Http\Controllers\KabidController::class, 'storeDisposisi'])->name('kabid.disposisi.store');
        Route::get('/kabid/tracking/{id}', [\App\Http\Controllers\PnsController::class, 'detail'])->name('kabid.tracking.detail');
    });

    // Route untuk Admin OPD (Role 2)
    Route::middleware('role:2')->group(function () {
        Route::get('/opd', [\App\Http\Controllers\OpdController::class, 'index'])->name('opd.index');
        Route::get('/opd/usulan/create', [\App\Http\Controllers\OpdController::class, 'create'])->name('opd.usulan.create');
        Route::post('/opd/usulan', [\App\Http\Controllers\OpdController::class, 'store'])->name('opd.usulan.store');
        Route::get('/opd/riwayat', [\App\Http\Controllers\OpdController::class, 'riwayat'])->name('opd.riwayat');
        Route::get('/opd/tracking/{id}', [\App\Http\Controllers\OpdController::class, 'detail'])->name('opd.tracking.detail');
    });

    // Route untuk Super Admin & Staff Verifikator (Role 1)
    Route::middleware('role:1')->group(function () {
        Route::get('/bkpsdm', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
        Route::post('/bkpsdm/verifikasi/{id}', [\App\Http\Controllers\AdminController::class, 'storeVerifikasi'])->name('admin.verifikasi.store');
        Route::post('/bkpsdm/upload-sk/{id}', [\App\Http\Controllers\AdminController::class, 'uploadSK'])->name('admin.upload-sk');
        Route::get('/bkpsdm/tracking', [\App\Http\Controllers\AdminController::class, 'riwayat'])->name('admin.tracking');
        Route::get('/bkpsdm/tracking/{id}', [\App\Http\Controllers\PnsController::class, 'detail'])->name('admin.tracking.detail');
        Route::get('/bkpsdm/manage-users', [\App\Http\Controllers\Admin\ManageUserController::class, 'index'])->name('admin.manage-users');
        Route::post('/bkpsdm/manage-users', [\App\Http\Controllers\Admin\ManageUserController::class, 'store'])->name('admin.manage-users.store');
        Route::put('/bkpsdm/manage-users/{id}', [\App\Http\Controllers\Admin\ManageUserController::class, 'update'])->name('admin.manage-users.update');
        Route::delete('/bkpsdm/manage-users/{id}', [\App\Http\Controllers\Admin\ManageUserController::class, 'destroy'])->name('admin.manage-users.destroy');

        Route::get('/bkpsdm/manage-dokumen', [\App\Http\Controllers\Admin\ManageDokumenController::class, 'index'])->name('admin.manage-dokumen');
        Route::post('/bkpsdm/manage-dokumen', [\App\Http\Controllers\Admin\ManageDokumenController::class, 'store'])->name('admin.manage-dokumen.store');
        Route::put('/bkpsdm/manage-dokumen/{id}', [\App\Http\Controllers\Admin\ManageDokumenController::class, 'update'])->name('admin.manage-dokumen.update');
        Route::delete('/bkpsdm/manage-dokumen/{id}', [\App\Http\Controllers\Admin\ManageDokumenController::class, 'destroy'])->name('admin.manage-dokumen.destroy');
        Route::get('/bkpsdm/audit-trail', [\App\Http\Controllers\Admin\AuditTrailController::class, 'index'])->name('admin.audit-trail');
        Route::get('/bkpsdm/settings', function () {})->name('admin.settings');
        Route::post('/bkpsdm/siasn-sync/{id}', [\App\Http\Controllers\AdminController::class, 'syncSiasn'])->name('admin.siasn.sync');
    });

    // ==========================================
    // MODULE EKSEKUTIF (AKSES GABUNGAN 0, 1, 4)
    // ==========================================
    Route::middleware('role:0,1,4')->group(function () {
        Route::get('/admin/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('admin.laporan');
    });

    // Rute Global untuk Input Catatan Revisi / Pesan Usulan (bisa diakses PNS, Admin OPD, Staf Teknis PKBM)
    Route::post('/usulan/pesan', [\App\Http\Controllers\UsulanPesanController::class, 'store'])->name('usulan.pesan.store');
});


require __DIR__.'/auth.php';
