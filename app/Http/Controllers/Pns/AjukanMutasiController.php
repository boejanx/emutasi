<?php

namespace App\Http\Controllers\Pns;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UsulanService;
use App\Models\Usulan;
use App\Models\RefDokumen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AjukanMutasiController extends Controller
{
    protected $usulanService;

    public function __construct(UsulanService $usulanService)
    {
        $this->usulanService = $usulanService;
    }

    public function create()
    {
        // Status besides 4 (Selesai), 5 (SK Terbit), and 98 (Ditolak)
        $activeUsulan = Usulan::where('id_user', auth()->id())
            ->whereNotIn('status', [4, 5, 98])
            ->exists();

        if ($activeUsulan) {
            return redirect()->route('pns.index')->with('error', 'Akses Diblokir! Anda masih memiliki usulan yang sedang diproses.');
        }

        $dokumenSyarat = RefDokumen::where('status', 1)->get();
        return view('pns.usulan.create', compact('dokumenSyarat'));
    }

    public function store(Request $request)
    {
        // Block submission if somehow user bypassed the UI button lock
        $activeUsulan = Usulan::where('id_user', auth()->id())
            ->whereNotIn('status', [4, 5, 98])
            ->exists();

        if ($activeUsulan) {
            return redirect()->route('pns.index')->with('error', 'Akses Diblokir! Anda masih memiliki usulan yang sedang diproses.');
        }

        // Fetch active documents
        $dokumenSyarat = RefDokumen::where('status', 1)->get();

        // Dynamically build validation rules
        $rules = [
            'no_surat' => 'required|string',
            'tanggal_surat' => 'required|date',
            'perihal' => 'required|string',
            'no_whatsapp' => 'required|string',
            'details.0.nip' => 'required|string',
            'details.0.nama' => 'required|string',
            'details.0.jabatan' => 'required|string',
            'details.0.lokasi_awal' => 'required|string',
            'details.0.lokasi_tujuan' => 'required|string',
            'details.0.siasn_id' => 'nullable|string',
            'details.0.unor_id_tujuan' => 'nullable|string',
            'details.0.nama_unor_tujuan' => 'nullable|string',
        ];

        foreach ($dokumenSyarat as $dok) {
            $inputName = 'file_dokumen_temp_' . $dok->id_dokumen;
            $rules[$inputName] = 'required|string';
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();
            
            // Build the data structure expected by UsulanService
            $data = $request->only(['no_surat', 'tanggal_surat', 'perihal', 'no_whatsapp']);
            $detailData = $request->input('details')[0];
            
            // Handle file uploads dynamically
            $berkas = [];
            
            foreach ($dokumenSyarat as $dok) {
                $inputName = 'file_dokumen_temp_' . $dok->id_dokumen;
                if ($request->filled($inputName)) {
                    $path = $request->input($inputName);
                    // Store the ID or a logical name; using ID from ref_dokumen here
                    $berkas[] = ['id_dokumen' => $dok->id_dokumen, 'path_dokumen' => $path];
                }
            }

            // Append berkas to detail
            $detailData['berkas'] = $berkas;
            $data['details'] = [$detailData]; // Put it back into array format expected by service

            $userId = auth()->id() ?? 1; // Assuming auth check is in place, defaulting for safety
            
            // Call the service we created earlier
            $this->usulanService->createUsulan($data, $userId);

            DB::commit();
            return redirect()->route('pns.index')->with('success', 'Usulan Mutasi berhasil diajukan!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal Submit Ajukan Mutasi', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal mengajukan usulan: ' . $e->getMessage())->withInput();
        }
    }

    public function revisiBerkas(Request $request, $id_berkas)
    {
        $request->validate([
            'file_revisi' => 'required|file|mimes:pdf|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $berkas = \App\Models\UsulanBerkas::with(['detail.usulan', 'dokumen'])->findOrFail($id_berkas);
            
            // Store new file
            $path = $request->file('file_revisi')->store('berkas_mutasi', 'public');
            
            // Update berkas row: ganti file & reset status verifikasi → 0 (pending)
            $berkas->update([
                'path_dokumen' => $path,
                'status'       => 0,
            ]);

            $detail = $berkas->detail;
            if ($detail && $detail->usulan) {
                $usulan       = $detail->usulan;
                $namaDokumen  = $berkas->dokumen->nama_dokumen ?? 'Dokumen';

                // Catat log revisi ke tracking (positional arguments)
                $this->usulanService->logUsulan(
                    $usulan->id_usulan,
                    'UPLOAD_REVISI',
                    'Menunggu Verifikasi Ulang',
                    'PNS melakukan unggah ulang revisi dokumen: ' . $namaDokumen,
                    auth()->id()
                );

                // Kembalikan usulan ke inbox admin:
                // status=3 (Diproses), disposisi=2 (Staf/Admin)
                $usulan->update([
                    'status'    => 3,
                    'disposisi' => 2,
                ]);

                // Reset status detail juga agar tidak lagi muncul badge "Revisi Berkas"
                $detail->update(['status' => 0]);
            }

            DB::commit();

            return redirect()->route('pns.tracking.detail', $detail->id_usulan)
                ->with('success', 'Revisi berkas berhasil diunggah! Berkas akan ditinjau ulang oleh Verifikator.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal Upload Revisi', ['error' => $e->getMessage(), 'id_berkas' => $id_berkas]);
            return back()->with('error', 'Gagal mengunggah revisi: ' . $e->getMessage());
        }
    }

    public function uploadTemp(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:2048'
        ]);

        try {
            if ($request->hasFile('file')) {
                // Secara opsional, bisa disimpan ke folder khusus 'temp' yang kemudian dipindah ke 'berkas_mutasi' saat divalidasi
                $path = $request->file('file')->store('berkas_mutasi', 'public');
                return response()->json([
                    'status' => 'success',
                    'path' => $path
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Gagal Upload Temp', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengunggah dokumen sementara'
            ], 500);
        }
    }
}
