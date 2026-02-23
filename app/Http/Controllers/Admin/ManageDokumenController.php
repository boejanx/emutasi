<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RefDokumen;

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

        RefDokumen::create([
            'nama_dokumen' => $request->nama_dokumen,
            'wajib' => $request->wajib,
            'status' => $request->status,
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

        return redirect()->back()->with('success', 'Berkas Persyaratan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $dok = RefDokumen::findOrFail($id);
        $dok->delete();

        return redirect()->back()->with('success', 'Berkas Persyaratan berhasil dihapus.');
    }
}
