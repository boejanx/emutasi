<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usulan;
use App\Services\UsulanService;

class PimpinanController extends Controller
{
    protected $usulanService;

    public function __construct(UsulanService $usulanService)
    {
        $this->usulanService = $usulanService;
    }

    public function inbox()
    {
        // For Pimpinan (Role 0), show incoming Usulan (status >= 1) AND not disposed yet
        $usulans = Usulan::with(['user', 'details', 'logs' => function($query) {
            $query->orderBy('created_at', 'asc');
        }, 'logs.user'])
        ->where('status', '>=', 1)
        ->where('disposisi', 0) // Only undisposed ones
        ->latest()->get();
        
        return view('pimpinan.inbox', compact('usulans'));
    }

    public function riwayat()
    {
        // Show Usulan that have already been passed on by Kabid (disposisi >= 2)
        $usulans = Usulan::with(['user', 'details.berkas.dokumen', 'logs' => function($query) {
            $query->orderBy('created_at', 'asc');
        }, 'logs.user'])
        ->where('status', '>=', 1)
        ->where('disposisi', '>=', 1)
        ->latest()->get();
        
        return view('pimpinan.riwayat', compact('usulans'));
    }

    public function storeDisposisi(Request $request, $id)
    {
        $request->validate([
            'tujuan_disposisi' => 'required|integer', // 1: Kepala Bidang, 2: Staf Teknis
            'catatan' => 'nullable|string'
        ]);

        $usulan = Usulan::findOrFail($id);

        $targetStr = $request->tujuan_disposisi == 1 ? 'Kepala Bidang Mutasi' : 'Staf Teknis';
        
        // If status was 'Diajukan' (1), promote it to 'Diproses' (3)
        $newStatus = $usulan->status == 1 ? 3 : $usulan->status;

        $usulan->update([
            'disposisi' => $request->tujuan_disposisi,
            'status' => $newStatus
        ]);

        // Log Action
        $this->usulanService->logUsulan(
            $usulan->id_usulan,
            'DISPOSISI_SURAT',
            'Diproses',
            "Disposisi diteruskan ke " . $targetStr . ". Catatan Pimpinan: " . ($request->catatan ?? '-')
        );

        return back()->with('success', 'Surat usulan berhasil didisposisikan ke ' . $targetStr);
    }
}
