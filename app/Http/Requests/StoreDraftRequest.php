<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDraftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorize at controller level or here
    }

    public function rules(): array
    {
        return [
            'no_surat' => 'nullable|string|max:255',
            'tanggal_surat' => 'nullable|date',
            'perihal' => 'nullable|string|max:255',
            'no_whatsapp' => 'nullable|string|max:20',
            'nomor_sk' => 'nullable|string|max:255',
            'path_sk' => 'nullable|string',
            'details' => 'nullable|array',
            'details.*.nip' => 'nullable|string',
            'details.*.nama' => 'nullable|string',
            'details.*.jabatan' => 'nullable|string',
            'details.*.lokasi_awal' => 'nullable|string',
            'details.*.lokasi_tujuan' => 'nullable|string',
            'details.*.catatan' => 'nullable|string',
            'details.*.siasn_id' => 'nullable|string',
            'details.*.unor_id_tujuan' => 'nullable|string',
            'details.*.nama_unor_tujuan' => 'nullable|string',
            'details.*.tempat_lahir' => 'nullable|string',
            'details.*.tanggal_lahir' => 'nullable|string',
            'details.*.pangkat_akhir' => 'nullable|string',
            'details.*.gol_ruang_akhir' => 'nullable|string',
            'details.*.tmt_gol_akhir' => 'nullable|string',
            'details.*.pendidikan_terakhir_nama' => 'nullable|string',
            'details.*.jabatan_nama' => 'nullable|string',
            'details.*.unor_nama' => 'nullable|string',
            'details.*.unor_induk_nama' => 'nullable|string',
            'details.*.berkas' => 'nullable|array',
            'details.*.berkas.*.path_dokumen' => 'nullable|string',
            'details.*.berkas.*.id_dokumen' => 'nullable|string',
        ];
    }
}
