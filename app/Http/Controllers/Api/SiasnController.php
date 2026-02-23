<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SiasnApiService;

class SiasnController extends Controller
{
    protected $siasnService;

    public function __construct(SiasnApiService $siasnService)
    {
        $this->siasnService = $siasnService;
    }

    public function getPegawaiData($nip)
    {
        try {
            $data = $this->siasnService->getDataUtama($nip);

            if (isset($data['data']) && !empty($data['data'])) {
                // Ekstrak data yang diperlukan (Hanya contoh respon sesuai standard SIASN)
                $pns = $data['data'];
                $nama = ($pns['gelarDepan'] ? $pns['gelarDepan'] . ' ' : '') . 
                        $pns['nama'] . 
                        ($pns['gelarBelakang'] ? ', ' . $pns['gelarBelakang'] : '');

                return response()->json([
                    'success' => true,
                    'data' => [
                        'nip' => $pns['nipBaru'],
                        'nama' => trim($nama),
                        'jabatan' => $pns['jabatanNama'] ?? '-',
                        'lokasi_awal' => $pns['unorNama'] ?? '-',
                        'pangkat' => $pns['golRuangAkhir'] ?? '-',
                    ]
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Data PNS tidak ditemukan di SIASN BKN.']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal terhubung ke web service SIASN BKN.', 'error' => $e->getMessage()]);
        }
    }
}
