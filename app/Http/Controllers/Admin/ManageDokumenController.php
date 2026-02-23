<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RefDokumen;
use App\Models\SystemLog;

class ManageDokumenController extends Controller
{
    public function index()
    {
        $dokumen = RefDokumen::latest()->get();
        return view('admin.dokumen.index', compact('dokumen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'wajib' => 'required|boolean',
            'status' => 'required|boolean',
        ]);

        $dokumen = RefDokumen::create([
            'nama_dokumen' => $request->nama_dokumen,
            'wajib' => $request->wajib,
            'status' => $request->status,
        ]);

        SystemLog::create([
            'id_user' => auth()->id() ?? 1,
            'action' => 'TAMBAH_DOKUMEN_SYARAT',
            'description' => 'Menambahkan referensi dokumen syarat baru: ' . $dokumen->nama_dokumen,
            'ip_address' => request()->ip() ?? '127.0.0.1',
            'user_agent' => request()->userAgent() ?? 'System',
        ]);

        return redirect()->back()->with('success', 'Berkas Persyaratan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $dok = RefDokumen::findOrFail($id);
        
        $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'wajib' => 'required|boolean',
            'status' => 'required|boolean',
        ]);

        $dok->update([
            'nama_dokumen' => $request->nama_dokumen,
            'wajib' => $request->wajib,
            'status' => $request->status,
        ]);

        SystemLog::create([
            'id_user' => auth()->id() ?? 1,
            'action' => 'UPDATE_DOKUMEN_SYARAT',
            'description' => 'Memperbarui referensi dokumen syarat: ' . $dok->nama_dokumen,
            'ip_address' => request()->ip() ?? '127.0.0.1',
            'user_agent' => request()->userAgent() ?? 'System',
        ]);

        return redirect()->back()->with('success', 'Berkas Persyaratan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $dok = RefDokumen::findOrFail($id);
        $namaDok = $dok->nama_dokumen;
        
        $dok->delete();

        SystemLog::create([
            'id_user' => auth()->id() ?? 1,
            'action' => 'HAPUS_DOKUMEN_SYARAT',
            'description' => 'Menghapus referensi dokumen syarat: ' . $namaDok,
            'ip_address' => request()->ip() ?? '127.0.0.1',
            'user_agent' => request()->userAgent() ?? 'System',
        ]);

        return redirect()->back()->with('success', 'Berkas Persyaratan berhasil dihapus.');
    }
}
