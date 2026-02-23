<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usulan;
use App\Models\RefDokumen;

class PnsController extends Controller
{
    public function index()
    {
        // Cari usulan yang sedang aktif (masih dalam proses verifikasi/progres)
        // Status: 0=Draft, 1=Diajukan, 2=Disposisi, 3=Diproses
        // Status 4 (Selesai) dan 99 (Ditolak/Revisi) dianggap sudah memiliki keputusan final/tindakan lanjut.
        // Namun jika 99 adalah 'Revisi Berkas', biasanya dianggap masih aktif. 
        // Sesuai permintaan user: Jika Selesai/Ditolak boleh mengajukan lagi.
        
        $activeUsulan = Usulan::where('id_user', auth()->id())
            ->whereNotIn('status', [4, 5, 99])
            ->first();
            
        $lastUsulan = Usulan::where('id_user', auth()->id())->latest()->first();
        
        $hasActiveUsulan = $activeUsulan ? true : false;
        $dokumenSyarat = RefDokumen::where('status', 1)->get();
        
        return view('pns.index', compact('hasActiveUsulan', 'lastUsulan', 'dokumenSyarat'));
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
