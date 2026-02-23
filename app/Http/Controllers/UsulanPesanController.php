<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\UsulanPesan;
use App\Models\SystemLog;

class UsulanPesanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_usulan' => 'required|exists:tb_usulan,id_usulan',
            'pesan' => 'required|string|max:1000'
        ]);

        UsulanPesan::create([
            'id_usulan' => $request->id_usulan,
            'id_user' => auth()->id(),
            'pesan' => $request->pesan
        ]);

        // Catat Audit Trail
        SystemLog::create([
            'id_user' => auth()->id(),
            'action' => 'KIRIM_PESAN',
            'description' => 'Mengirim pesan/catatan revisi pada Usulan: ' . $request->id_usulan,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        // Refresh/back ke halaman sebelumnya
        return redirect()->back()->with('success', 'Pesan berhasil dikirim.');
    }
}
