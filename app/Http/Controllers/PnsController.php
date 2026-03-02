<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usulan;
use App\Models\RefDokumen;

class PnsController extends Controller
{
    public function index()
    {
        // Draft: status 0 (belum dikirim, hanya milik user sendiri)
        $activeDraft = Usulan::where('id_user', auth()->id())
            ->where('status', 0)
            ->latest()
            ->first();

        // Usulan aktif yang sudah dikirim (in-progress/revisi, bukan draft)
        $activeUsulan = Usulan::where('id_user', auth()->id())
            ->whereNotIn('status', [0, 4, 5, 98]) // 0=Draft, 4=Selesai, 5=SK, 98=Tolak
            ->first();
            
        $lastUsulan = Usulan::where('id_user', auth()->id())->latest()->first();
        
        $hasActiveDraft  = $activeDraft  ? true : false;
        $hasActiveUsulan = $activeUsulan ? true : false;
        $dokumenSyarat   = RefDokumen::where('status', 1)->get();
        
        return view('pns.index', compact('hasActiveDraft', 'activeDraft', 'hasActiveUsulan', 'lastUsulan', 'dokumenSyarat', 'activeUsulan'));
    }

    public function tracking()
    {
        $usulans = Usulan::where('id_user', auth()->id())->latest()->get();
        return view('pns.tracking', compact('usulans'));
    }

    public function detail($id)
    {
        $user = auth()->user();
        $query = Usulan::with(['details.berkas.dokumen', 'logs.user', 'siasnUnorJabatan']);

        // Jika bukan Pimpinan, Admin BKPSDM, atau Kabid, maka hanya boleh melihat usulan miliknya sendiri
        if (!in_array($user->role, [0, 1, 4])) {
            $query->where('id_user', $user->id);
        }

        $data = $query->where('id_usulan', $id)->firstOrFail();
        
        return view('pns.detail', compact('data'));
    }
}
