<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usulan;
use App\Services\UsulanService;

class KabidController extends Controller
{
    protected $usulanService;

    public function __construct(UsulanService $usulanService)
    {
        $this->usulanService = $usulanService;
    }

    public function inbox()
    {
        // For Kabid (Role 4), show Usulan mapped to their desk (disposisi == 1)
        $usulans = Usulan::with(['user', 'details', 'logs' => function($query) {
            $query->orderBy('created_at', 'asc');
        }, 'logs.user'])
        ->where('status', '>=', 1)
        ->where('disposisi', 1)
        ->latest()->get();
        
        return view('kabid.inbox', compact('usulans'));
    }

    public function riwayat()
    {
        // Show Usulan that have already been passed on by Kabid (disposisi >= 2)
        $usulans = Usulan::with(['user', 'details.berkas.dokumen', 'logs' => function($query) {
            $query->orderBy('created_at', 'asc');
        }, 'logs.user'])
        ->where('status', '>=', 1)
        ->where('disposisi', '>=', 2)
        ->latest()->get();
        
        return view('kabid.riwayat', compact('usulans'));
    }

    public function storeDisposisi(Request $request, $id)
    {
        $request->validate([
            'tujuan_disposisi' => 'required|integer', // Usually they can only pass to Staf Teknis (2)
            'catatan' => 'nullable|string'
        ]);

        $usulan = Usulan::findOrFail($id);

        $targetStr = 'Staf Teknis'; // Default Kabid passes to Staf Teknis
        
        // Prevent bouncing back or moving incorrectly. Enforce destination = 2 (Staf Teknis)
        $usulan->update([
            'disposisi' => 2,
            'status' => 3 // Diproses
        ]);

        // Log Action
        $this->usulanService->logUsulan(
            $usulan->id_usulan,
            'DISPOSISI_SURAT',
            'Diproses',
            "Disposisi diteruskan oleh Kepala Bidang ke " . $targetStr . ". Catatan Kabid: " . ($request->catatan ?? '-')
        );

        return back()->with('success', 'Surat usulan berhasil didisposisikan ke ' . $targetStr);
    }
}
