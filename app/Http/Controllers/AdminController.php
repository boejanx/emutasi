<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usulan;
use App\Services\UsulanService;
use App\Services\SiasnApiService;
use App\Models\SiasnUnorJabatan;
use App\Models\SiasnJabatan;
use App\Models\SiasnJabatanFungsional;
use App\Models\SiasnJabatanPelaksana;
use Illuminate\Support\Facades\Log;
use App\Models\TemplateSk;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;


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
        
        $templates = TemplateSk::where('is_active', true)->get();

        return view('admin.index', compact('usulans', 'templates'));
    }

    public function riwayat()
    {
        // Show Usulan that Admin has finished processing (disposisi >= 3 or status > 3)
        // Or whatever defines "riwayat" for admin
        $usulans = Usulan::with(['user', 'details.berkas.dokumen', 'siasnUnorJabatan', 'logs' => function($query) {
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
            'status_verifikasi' => 'required|in:terima,tolak,revisi',
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
        } elseif ($request->status_verifikasi === 'revisi') {
            $usulan->update([
                'status'    => 99, // Berkas Perlu Revisi
                'disposisi' => 3   // Selesai di inbox admin (kembali ke instansi/PNS)
            ]);

            $this->usulanService->logUsulan(
                $usulan->id_usulan,
                'BERKAS_REVISI',
                'Status Revisi',
                'Verifikasi selesai. PNS perlu memperbaiki berkas yang ditolak. Catatan: ' . ($request->catatan ?? '-')
            );

            return back()->with('warning', 'Usulan dikembalikan untuk revisi. Berkas yang tidak disetujui harus di-upload ulang oleh pengusul.');
        } else {
            // Tolak Mutlak
            $usulan->update([
                'status'    => 98, // Tolak Permanen
                'disposisi' => 3   // Selesai
            ]);

            $this->usulanService->logUsulan(
                $usulan->id_usulan,
                'VERIFIKASI_DITOLAK',
                'Berkas Ditolak',
                'Usulan ditolak permanen. Catatan: ' . ($request->catatan ?? '-')
            );

            return back()->with('error', 'Usulan telah ditolak secara peringatan. Pihak pengusul tidak dapat mengunggah revisi pada usulan ini.');
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
                SiasnUnorJabatan::updateOrCreate(
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
            Log::error('Gagal Upload SK', ['error' => $e->getMessage()]);
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
            $siasnService = new SiasnApiService();
            
            $detail = $usulan->details->first();
            
            if (!$detail) {
                throw new \Exception('Data detail usulan tidak ditemukan.');
            }

            $jabatanFungsionalId = '';
            $jabatanFungsionalUmumId = '';
            
            if ($detail->jenis_jabatan_baru === 'jft') {
                $jabatanFungsionalId = $detail->jabatan_baru_id;
            } elseif ($detail->jenis_jabatan_baru === 'jfu') {
                $jabatanFungsionalUmumId = $detail->jabatan_baru_id;
            } else {
                $jabatanFungsionalId = $siasnData->jabatan_fungsional_id ?? '';
                $jabatanFungsionalUmumId = $siasnData->jabatan_fungsional_umum_id ?? '';
            }
            
            $unorIdToPost = $detail->sub_unor_id;

            $payload = [
                'id'                        => null,
                'pnsId'                     => $detail->pns_id ?? ($detail->siasn_id ?? $siasnData->pns_id),
                'unorId'                    => $unorIdToPost,
                'eselonId'                  => null,
                'jabatanFungsionalId'       => $jabatanFungsionalId,
                'subJabatanId'              => null,
                'jabatanFungsionalUmumId'   => $jabatanFungsionalUmumId,
                'instansiId'                => 'A5EB03E241F4F6A0E040640A040252AD',
                'instansiIndukId'           => 'A5EB03E241F4F6A0E040640A040252AD',
                'satuanKerjaId'             => 'A5EB03E241F4F6A0E040640A040252AD',
                'tmtJabatan'                => date('d-m-Y', strtotime($siasnData->tmt_jabatan)), 
                'tmtPelantikan'             => date('d-m-Y', strtotime($siasnData->tmt_pelantikan)),
                'tmtMutasi'                 => date('d-m-Y', strtotime($siasnData->tmt_jabatan)),
                'nomorSk'                   => $siasnData->nomor_sk,
                'tanggalSk'                 => date('d-m-Y', strtotime($siasnData->tanggal_sk)),
                'jenisMutasiId'             => 'MU', // Mutasi Jabatan
                'jenisPenugasanId'          => null,
                'jenisJabatan'              => ($detail->jenis_jabatan_baru === 'jft') ? '2' : (($detail->jenis_jabatan_baru === 'jfu') ? '4'  : '1'),
            ];

            // Setup Dokumen / File Path for includeDokumen
            $skUrl = null;
            if ($usulan->path_sk) {
                $skUrl = storage_path('app/public/' . $usulan->path_sk);
            }

            // 3. Post data ke SIASN
            if ($skUrl) {
                $response = $siasnService->postUnorJabatanWithDokumen($payload, $skUrl);
            } else {
                $response = $siasnService->postUnorJabatan($payload);
            }

            $rwJabatanId = $response['data']['rwJabatanId'] ?? ($response['mapData']['rwJabatanId'] ?? '');
            
            // 4. Update tabel setelah status sukses (Dapat id riwayat)
            $siasnData->update([
                'is_sync' => true,
                'pns_id'  => $detail->pns_id ?? ($detail->siasn_id ?? $siasnData->pns_id),
                'id_riwayat_jabatan_siasn' => $rwJabatanId,
                'sync_response' => $response
            ]);

            // Assign the SIASN response id to detail's siasn_id mapping
            if ($detail) {
                $detail->update([
                    'siasn_id' => $rwJabatanId
                ]);
            }

            $this->usulanService->logUsulan(
                $usulan->id_usulan,
                'SYNC_SIASN',
                'Tersinkronisasi SIASN',
                'Data riwayat perpindahan jabatan berhasil di-push secara otomatis ke database SIASN BKN.',
                auth()->id()
            );

            return back()->with('success', 'Berhasil melakukan sinkronisasi dengan BKN (SIASN) !');

        } catch (\Exception $e) {
            Log::error('SIASN Sync Error', ['usulan_id' => $id, 'error' => $e->getMessage()]);
            
            // Track failure string for debugging
            if (isset($siasnData)) {
                $siasnData->update([
                    'sync_response' => ['error' => $e->getMessage()]
                ]);
            }
            
            return back()->with('error', 'Gagal tersinkronisasi ke SIASN BKN: ' . $e->getMessage());
        }
    }

    public function buatDraftSk(Request $request, $id)
    {
        $request->validate([
            'template_id' => 'required|exists:template_sks,id',
            'sub_unor_id' => 'required|string',
            'sub_unor_nama' => 'required|string',
            'jenis_jabatan_baru' => 'required|string|in:jft,jfu',
            'jabatan_baru_id' => 'required|string',
            'jabatan_baru_nama' => 'required|string'
        ]);

        $usulan = Usulan::with(['user', 'details'])->findOrFail($id);
        $template = TemplateSk::findOrFail($request->template_id);

        try {
            $templatePath = storage_path('app/public/' . $template->file_path);
            
            if (!file_exists($templatePath)) {
                return back()->with('error', 'File template fisik tidak ditemukan di server.');
            }

            Settings::setOutputEscapingEnabled(true);
            $templateProcessor = new TemplateProcessor($templatePath);
            
            $detail = $usulan->details->first();
            
            // Simpan riwayat inputan modal ke tabel UsulanDetail agar bisa digunakan pada saat sync SK Final
            if ($detail) {
                $detail->update([
                    'jenis_jabatan_baru' => $request->jenis_jabatan_baru,
                    'jabatan_baru_id' => $request->jabatan_baru_id,
                    'jabatan_baru_nama' => $request->jabatan_baru_nama,
                    'sub_unor_id' => $request->sub_unor_id,
                    'sub_unor_nama' => $request->sub_unor_nama,
                ]);
            }

            // Format tanggal jika dimungkinkan
            $tgl_lahir = $detail->tanggal_lahir ? (Carbon::hasFormat($detail->tanggal_lahir, 'd-m-Y') ? Carbon::createFromFormat('d-m-Y', $detail->tanggal_lahir)->translatedFormat('d F Y') : (Carbon::hasFormat($detail->tanggal_lahir, 'Y-m-d') ? Carbon::parse($detail->tanggal_lahir)->translatedFormat('d F Y') : $detail->tanggal_lahir)) : '-';
            
            $tmt_gol = $detail->tmt_gol_akhir ? (Carbon::hasFormat($detail->tmt_gol_akhir, 'd-m-Y') ? Carbon::createFromFormat('d-m-Y', $detail->tmt_gol_akhir)->translatedFormat('d F Y') : (Carbon::hasFormat($detail->tmt_gol_akhir, 'Y-m-d') ? Carbon::parse($detail->tmt_gol_akhir)->translatedFormat('d F Y') : $detail->tmt_gol_akhir)) : '-';
            
            $templateProcessor->setValue('no_urut', '1');
            $templateProcessor->setValue('no_surat', $usulan->no_surat ?? '-');
            $templateProcessor->setValue('nama_pns', $detail->nama ?? '-');
            $templateProcessor->setValue('nip', $detail->nip ?? '-');
            $templateProcessor->setValue('tempat_lahir', $detail->tempat_lahir ?? '-');
            $templateProcessor->setValue('tanggal_lahir', $tgl_lahir);
            
            // Pangkat dan Golongan kini sudah terpisah langsung dari API SIASN:
            // "pangkat_akhir" (Misal: "Pembina"), "gol_ruang_akhir" (Misal: "IV/a")
            $pangkat = $detail->pangkat_akhir ?? '-';
            // Jika kosong, sediakan fallback
            $gol = $detail->gol_ruang_akhir ?? '-';

            $templateProcessor->setValue('pangkat', $pangkat);
            $templateProcessor->setValue('gol', $gol);
            $templateProcessor->setValue('tmt_gol', $tmt_gol);
            
            $templateProcessor->setValue('pendidikan', $detail->pendidikan_terakhir_nama ?? '-');
            $templateProcessor->setValue('jabatan', $detail->jabatan_nama ?? $detail->jabatan ?? '-');
            $templateProcessor->setValue('unit_kerja', $detail->unor_induk_nama ?? $detail->lokasi_awal ?? '-');
            
            // Jabatan baru asumsi sama, unor_baru menggunakan sub unor yang dipilih dari modal
            // Jika untuk alasan tertentu sub unor kosong (fallback), kembali ke unor_tujuan asal
            $templateProcessor->setValue('jabatan_baru', $request->jabatan_baru_nama ?? $detail->jabatan_nama ?? $detail->jabatan ?? '-');
            $templateProcessor->setValue('sub_unor_baru', $request->sub_unor_nama ?? $detail->nama_unor_tujuan ?? '-');
            $templateProcessor->setValue('unor_baru', $request->unor_nama ?? $detail->nama_unor_tujuan ?? '-');

            
            // Tanggal SK
            \Carbon\Carbon::setLocale('id');
            $templateProcessor->setValue('tanggal_sk', \Carbon\Carbon::now()->translatedFormat('d F Y'));
            
            $filename = 'draft_sk_' . $usulan->id_usulan . '_' . time() . '.docx';
            $outputPath = storage_path('app/public/draft_sks/' . $filename);

            if (!file_exists(storage_path('app/public/draft_sks'))) {
                mkdir(storage_path('app/public/draft_sks'), 0755, true);
            }

            $templateProcessor->saveAs($outputPath);
            
            // Delete old draft if exists
            if ($usulan->draft_sk_path && Storage::disk('public')->exists($usulan->draft_sk_path)) {
                Storage::disk('public')->delete($usulan->draft_sk_path);
            }

            $usulan->update([
                'draft_sk_path' => 'draft_sks/' . $filename
            ]);

            return back()->with('success', 'Draft SK berhasil dibuat dan siap diunduh.');

        } catch (\Exception $e) {
            Log::error('Draft SK Error', ['msg' => $e->getMessage()]);
            return back()->with('error', 'Gagal membuat draft SK: ' . $e->getMessage());
        }
    }

    public function unduhDraftSk($id)
    {
        $usulan = Usulan::findOrFail($id);
        if (!$usulan->draft_sk_path || !Storage::disk('public')->exists($usulan->draft_sk_path)) {
            return back()->with('error', 'Draft SK tidak ditemukan.');
        }

        return Storage::disk('public')->download($usulan->draft_sk_path);
    }

    public function hapusDraftSk($id)
    {
        $usulan = Usulan::findOrFail($id);
        if ($usulan->draft_sk_path && Storage::disk('public')->exists($usulan->draft_sk_path)) {
            Storage::disk('public')->delete($usulan->draft_sk_path);
        }

        $usulan->update(['draft_sk_path' => null]);
        return back()->with('success', 'Draft SK berhasil dihapus. Anda dapat membuat draft baru.');
    }
}
