<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usulan;
use App\Services\UsulanService;

class AdminController extends Controller
{
    protected $usulanService;

    public function __construct(UsulanService $usulanService)
    {
        $this->usulanService = $usulanService;
    }

    public function index()
    {
        // For Admin / Staff Verifikator (Role 1)
        // Show Usulan that have been mapped to their desk (disposisi == 2)
        // Usually these are usulan that need verification or further processing
        $usulans = Usulan::with(['user', 'details.berkas.dokumen', 'logs' => function($query) {
            $query->orderBy('created_at', 'asc');
        }, 'logs.user'])
        ->where('status', '>=', 1)
        ->where('disposisi', '=', 2) // Disposisi 2 is for Staf Teknis / Admin
        ->latest()->get();
        
        return view('admin.index', compact('usulans'));
    }

    public function riwayat()
    {
        // Show Usulan that Admin has finished processing (disposisi >= 3 or status > 3)
        // Or whatever defines "riwayat" for admin
        $usulans = Usulan::with(['user', 'details.berkas.dokumen', 'logs' => function($query) {
            $query->orderBy('created_at', 'asc');
        }, 'logs.user'])
        ->where(function($query) {
            $query->where('disposisi', '>=', 3)
                  ->orWhereIn('status', [5, 99]);
        })
        ->latest()->get();
        
        return view('admin.riwayat', compact('usulans'));
    }

    public function storeVerifikasi(Request $request, $id)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:terima,tolak',
            'catatan' => 'nullable|string',
            'berkas_status' => 'required|array'
        ]);

        $usulan = Usulan::findOrFail($id);
        
        // Update per-berkas status
        foreach ($request->berkas_status as $id_berkas => $status) {
            \App\Models\UsulanBerkas::where('id_berkas', $id_berkas)->update([
                'status' => $status == 'terima' ? 1 : 2 // 1: valid, 2: tidak valid/tolak
            ]);
        }

        if ($request->status_verifikasi === 'terima') {
            $usulan->update([
                'status'    => 4, // Menunggu Upload SK
                'disposisi' => 2  // Tetap di Staf untuk upload SK
            ]);

            $this->usulanService->logUsulan(
                $usulan->id_usulan,
                'VERIFIKASI_SETUJU',
                'Menunggu Upload SK',
                'Verifikasi berkas selesai dan dinyatakan Sesuai. Menunggu admin mengunggah SK Mutasi. Catatan: ' . ($request->catatan ?? '-')
            );

            return back()->with('success', 'Berkas berhasil diverifikasi. Status: Menunggu Upload SK.');
        } else {
            $usulan->update([
                'status'    => 99, // Berkas Ditolak / Perlu Revisi
                'disposisi' => 3   // Selesai di inbox (pindah ke riwayat/tunggu revisi)
            ]);

            $this->usulanService->logUsulan(
                $usulan->id_usulan,
                'VERIFIKASI_DITOLAK',
                'Berkas Ditolak',
                'Verifikasi staf selesai. Terdapat berkas yang ditolak. Catatan: ' . ($request->catatan ?? '-')
            );

            return back()->with('success', 'Berkas ditolak. PNS perlu melakukan revisi.');
        }
    }

    public function uploadSK(Request $request, $id)
    {
        $request->validate([
            'nomor_sk' => 'required|string',
            'tanggal_sk' => 'required|date',
            'tmt_jabatan' => 'required|date',
            'tmt_pelantikan' => 'required|date',
            'file_sk'  => 'required|file|mimes:pdf|max:2048'
        ]);

        try {
            $usulan = Usulan::findOrFail($id);
            
            $path = $request->file('file_sk')->store('sk_mutasi', 'public');
            
            $usulan->update([
                'nomor_sk'  => $request->nomor_sk,
                'path_sk'   => $path,
                'status'    => 5, // Selesai / SK Terbit
                'disposisi' => 3  // Selesai
            ]);

            // Persiapkan draft SIASN
            if ($usulan->details && $usulan->details->count() > 0) {
                $detail = $usulan->details->first();
                \App\Models\SiasnUnorJabatan::updateOrCreate(
                    ['id_usulan' => $usulan->id_usulan],
                    [
                        'nip'            => $detail->nip,
                        'pns_id'         => $detail->siasn_id, // Gunakan siasn_id dari draft detail
                        'unor_id'        => $detail->unor_id_tujuan, // Pakai ID unor yang spesifik dari detail
                        'nomor_sk'       => $request->nomor_sk,
                        'tanggal_sk'     => $request->tanggal_sk,
                        'tmt_jabatan'    => $request->tmt_jabatan,
                        'tmt_pelantikan' => $request->tmt_pelantikan,
                        'is_sync'        => false,
                    ]
                );
            }

            $this->usulanService->logUsulan(
                $usulan->id_usulan,
                'UPLOAD_SK',
                'Selesai',
                'SK Mutasi telah diunggah dengan nomor: ' . $request->nomor_sk . '. Proses mutasi selesai.',
                auth()->id()
            );

            return back()->with('success', 'SK Mutasi berhasil diunggah! Usulan telah selesai.');
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal Upload SK', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal mengunggah SK: ' . $e->getMessage());
        }
    }

    public function syncSiasn($id)
    {
        $usulan = Usulan::with('siasnUnorJabatan')->findOrFail($id);
        
        $siasnData = $usulan->siasnUnorJabatan;
        if (!$siasnData) {
            return back()->with('error', 'Data riwayat SIASN tidak ditemukan atau belum diinisialisasi.');
        }

        if ($siasnData->is_sync) {
            return back()->with('success', 'Data ini sudah disinkronisasi ke SIASN.');
        }

        try {
            $siasnService = new \App\Services\SiasnApiService();
            
            // 1. Dapatkan PNS Id dan data lain dari API Data Utama SIASN (agar real-time dan akurat)
            $pnsDataRes = $siasnService->getDataUtama($siasnData->nip);
            $pnsUtama = $pnsDataRes['data'] ?? [];

            if (empty($pnsUtama) || empty($pnsUtama['id'])) {
                throw new \Exception('Data Utama PNS dengan NIP tersebut tidak ditemukan di BKN.');
            }

            // 2. Persiapkan payload mapping
            // Catatan: Payload mungkin membutuhkan field yang lebih detail (instansiId, jabatanFungsional dll) 
            // Apabila null, SIASN biasanya menghiraukan, atau default sesuai referensi if applicable.
            $payload = [
                'pnsId'          => $pnsUtama['id'],
                'unorId'         => $siasnData->unor_id,
                'eselonId'       => $siasnData->eselon_id ?? '',
                'jabatanFungsionalId'     => $siasnData->jabatan_fungsional_id ?? '',
                'jabatanFungsionalUmumId' => $siasnData->jabatan_fungsional_umum_id ?? '',
                'instansiId'              => $siasnData->instansi_id ?? $pnsUtama['instansiKerjaId'] ?? '',
                'satuanKerjaId'           => $siasnData->satuan_kerja_id ?? '',
                'tmtJabatan'              => date('d-m-Y', strtotime($siasnData->tmt_jabatan)), // Format BKN: d-m-Y (biasanya)
                'tmtPelantikan'           => date('d-m-Y', strtotime($siasnData->tmt_pelantikan)),
                'nomorSk'                 => $siasnData->nomor_sk,
                'tanggalSk'               => date('d-m-Y', strtotime($siasnData->tanggal_sk)),
            ];

            // 3. Post data
            $response = $siasnService->postUnorJabatan($payload);

            // 4. Update tabel setelah status sukses (Dapat id riwayat)
            $siasnData->update([
                'is_sync' => true,
                'pns_id'  => $pnsUtama['id'],
                'id_riwayat_jabatan_siasn' => $response['data']['rwJabatanId'] ?? ($response['mapData']['rwJabatanId'] ?? ''),
                'sync_response' => $response
            ]);

            $this->usulanService->logUsulan(
                $usulan->id_usulan,
                'SYNC_SIASN',
                'Tersinkronisasi SIASN',
                'Data riwayat perpindahan jabatan berhasil di-push secara otomatis ke database SIASN BKN.',
                auth()->id()
            );

            return back()->with('success', 'Berhasil melakukan sinkronisasi dengan BKN (SIASN) !');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SIASN Sync Error', ['usulan_id' => $id, 'error' => $e->getMessage()]);
            
            // Track failure string for debugging
            if (isset($siasnData)) {
                $siasnData->update([
                    'sync_response' => ['error' => $e->getMessage()]
                ]);
            }
            
            return back()->with('error', 'Gagal tersinkronisasi ke SIASN BKN: ' . $e->getMessage());
        }
    }
}
