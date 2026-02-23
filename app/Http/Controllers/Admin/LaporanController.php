<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usulan;
use Illuminate\Support\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Parameter Filter
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // Base Query untuk filter Bulan & Tahun
        $query = Usulan::query();
        
        if ($bulan != 'all') {
            $query->whereMonth('created_at', $bulan);
        }
        if ($tahun != 'all') {
            $query->whereYear('created_at', $tahun);
        }

        // Rekapitulasi Count
        $rekap = [
            'total' => (clone $query)->count(),
            'selesai' => (clone $query)->where('status', 5)->count(), // Status 5 = Selesai/SK Terbit
            'ditolak' => (clone $query)->where('status', 99)->count(), // Status 99 = Ditolak
            'proses' => (clone $query)->whereNotIn('status', [5, 99])->count(), // Selain selesai & ditolak = Proses
        ];

        // Rincian Daftar Usulan yang relevan di bulan tersebut
        $usulans = $query->with(['user', 'details.berkas.dokumen'])->latest()->get();
        if ($request->input('export') === 'excel') {
            $filename = "Laporan_Usulan_Mutasi_{$bulan}_{$tahun}.xlsx";
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\LaporanExport($rekap, $usulans, $bulan, $tahun), 
                $filename
            );
        }

        return view('admin.laporan.index', compact('rekap', 'usulans', 'bulan', 'tahun'));
    }
}
