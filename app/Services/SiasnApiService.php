<?php

namespace App\Services;

use SiASN\Sdk\SiasnClient;
use Illuminate\Support\Facades\Log;

class SiasnApiService
{
    protected $client;

    public function __construct()
    {
        $config = [
            "consumerKey"    => config('siasn.consumer_key'),
            "consumerSecret" => config('siasn.consumer_secret'),
            "clientId"       => config('siasn.client_id'),
            "ssoAccessToken" => config('siasn.sso_access_token')
        ];

        $this->client = new SiasnClient($config);
    }

    /**
     * Mendapatkan Data Utama PNS berdasarkan NIP.
     * 
     * @param string $nip
     * @return array|null
     */
    public function getDataUtama($nip)
    {
        try {
            return $this->client->pns()->dataUtama($nip);
        } catch (\Exception $e) {
            Log::error('SIASN GetDataUtama Error', [
                'nip' => $nip,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Mendapatkan Referensi UNOR dari SIASN.
     * Menggunakan cache lokal (argument true) untuk mempercepat respons.
     * 
     * @return array
     */
    public function getReferensiUnor()
    {
        try {
            return $this->client->referensi()->unor(true)->get();
        } catch (\Exception $e) {
            Log::error('SIASN GetReferensiUnor Error', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function getReferensiJabatanFungsional()
    {
        try {
            return $this->client->referensi()->jabatanFungsional(true)->get();
        } catch (\Exception $e) {
            Log::error('SIASN getReferensiJabatanFungsional Error', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function getReferensiJabatanPelaksana()
    {
        try {
            return $this->client->referensi()->jabatanPelaksana(true)->get();
        } catch (\Exception $e) {
            Log::error('SIASN getReferensiJabatanPelaksana Error', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
    /**
     * Membuat data Unor Jabatan ke SIASN.
     * Menggunakan method createUnorJabatan milik SiasnClient.
     * 
     * @param array $data payload request
     * @return array
     */
    public function postUnorJabatan(array $data)
    {
        try {
            return $this->client->jabatan()->createUnorJabatan($data)->save();
        } catch (\Exception $e) {
            Log::error('SIASN PostUnorJabatan Error', [
                'data' => $data,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Membuat data Unor Jabatan dengan Dokumen ke SIASN.
     * Menggunakan method createUnorJabatan lalu includeDokumen.
     * 
     * @param array $data payload request
     * @param string $fileUrl URL/path dokumen (binary file bisa juga)
     * @return array
     */
    public function postUnorJabatanWithDokumen(array $data, string $fileUrl)
    {
        try {
            return $this->client->jabatan()
                ->createUnorJabatan($data)
                ->includeDokumen($fileUrl)
                ->save();
        } catch (\Exception $e) {
            Log::error('SIASN PostUnorJabatanWithDokumen Error', [
                'data' => $data,
                'file_url' => $fileUrl,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
