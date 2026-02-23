<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UsulanService;
use App\Models\Usulan;
use App\Models\RefDokumen;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class OpdController extends Controller
{
    protected $usulanService;

    public function __construct(UsulanService $usulanService)
    {
        $this->usulanService = $usulanService;
    }

    public function index()
    {
        $user = Auth::user();
        $stats = [
            'total'         => Usulan::where('id_user', $user->id)->count(),
            'diproses'      => Usulan::where('id_user', $user->id)->whereNotIn('status', [5, 99])->count(),
            'selesai'       => Usulan::where('id_user', $user->id)->where('status', 5)->count(),
            'ditolak'       => Usulan::where('id_user', $user->id)->where('status', 99)->count(),
        ];

        $latest_usulans = Usulan::where('id_user', $user->id)
            ->with('details')
            ->latest()
            ->take(5)
            ->get();

        $dokumenSyarat = RefDokumen::where('status', 1)->get();

        return view('opd.index', compact('stats', 'latest_usulans', 'dokumenSyarat'));
    }

    public function create()
    {
        $dokumenSyarat = RefDokumen::where('status', 1)->get();
        return view('opd.usulan.create', compact('dokumenSyarat'));
    }

    public function store(Request $request)
    {
        $dokumenSyarat = RefDokumen::where('status', 1)->get();
        
        $rules = [
            'no_surat' => 'required|string',
            'tanggal_surat' => 'required|date',
            'perihal' => 'required|string',
            'no_whatsapp' => 'required|string',
            'details' => 'required|array|min:1',
            'details.*.nip' => 'required|string',
            'details.*.nama' => 'required|string',
            'details.*.jabatan' => 'required|string',
            'details.*.lokasi_awal' => 'required|string',
            'details.*.lokasi_tujuan' => 'required|string',
            'details.*.siasn_id' => 'nullable|string',
            'details.*.unor_id_tujuan' => 'nullable|string',
            'details.*.nama_unor_tujuan' => 'nullable|string',
        ];

        // File validation rules for each PNS
        foreach ($request->input('details', []) as $index => $detail) {
            foreach ($dokumenSyarat as $dok) {
                // Formatting input name: file_dokumen_{pns_index}_{dok_id}
                $inputName = "file_dokumen_{$index}_{$dok->id_dokumen}";
                $rules[$inputName] = 'required|file|mimes:pdf|max:2048';
            }
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();

            $data = $request->only(['no_surat', 'tanggal_surat', 'perihal', 'no_whatsapp']);
            $details = $request->input('details');
            $processedDetails = [];

            foreach ($details as $index => $detailData) {
                $berkas = [];
                foreach ($dokumenSyarat as $dok) {
                    $inputName = "file_dokumen_{$index}_{$dok->id_dokumen}";
                    if ($request->hasFile($inputName)) {
                        $path = $request->file($inputName)->store('berkas_mutasi', 'public');
                        $berkas[] = [
                            'id_dokumen' => $dok->id_dokumen,
                            'path_dokumen' => $path
                        ];
                    }
                }
                $detailData['berkas'] = $berkas;
                $processedDetails[] = $detailData;
            }

            $data['details'] = $processedDetails;
            $this->usulanService->createUsulan($data, auth()->id());

            DB::commit();
            return redirect()->route('opd.riwayat')->with('success', 'Usulan Mutasi berhasil diajukan untuk ' . count($processedDetails) . ' PNS!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('OpdController Store Error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal menyimpan usulan: ' . $e->getMessage())->withInput();
        }
    }

    public function riwayat()
    {
        $usulans = Usulan::where('id_user', auth()->id())
            ->with(['details.berkas.dokumen', 'logs' => function($query) {
                $query->orderBy('created_at', 'asc');
            }, 'logs.user'])
            ->latest()
            ->get();

        return view('opd.riwayat', compact('usulans'));
    }

    public function detail($id)
    {
        $usulan = Usulan::where('id_user', auth()->id())
            ->with(['details.berkas.dokumen', 'logs' => function($query) {
                $query->orderBy('created_at', 'asc');
            }, 'logs.user'])
            ->findOrFail($id);

        return view('opd.tracking_detail', compact('usulan'));
    }
}
