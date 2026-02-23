<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUsulanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'no_surat' => 'sometimes|required|string|max:255',
            'tanggal_surat' => 'sometimes|required|date',
            'perihal' => 'sometimes|required|string',
            'status' => 'sometimes|integer|in:0,1,2,3',
            'disposisi' => 'sometimes|integer|in:0,1,2',
            'details' => 'sometimes|array',
            'details.*.id_detail' => 'nullable|integer|exists:tb_usulan_detail,id_detail',
            'details.*.nip' => 'required_with:details|string|max:255',
            'details.*.nama' => 'required_with:details|string|max:255',
            'details.*.jabatan' => 'required_with:details|string|max:255',
            'details.*.lokasi_awal' => 'required_with:details|string|max:255',
            'details.*.lokasi_tujuan' => 'required_with:details|string|max:255',
            'details.*.status' => 'nullable|integer|in:0,1,2,3',
            'details.*.catatan' => 'nullable|string',
            'details.*.berkas' => 'nullable|array',
            'details.*.berkas.*.id_berkas' => 'nullable|integer|exists:tb_usulan_berkas,id_berkas',
            'details.*.berkas.*.path_dokumen' => 'required_with:details.*.berkas|string',
            'details.*.berkas.*.id_dokumen' => 'nullable|string',
            'details.*.berkas.*.status' => 'nullable|integer|in:0,1',
        ];
    }
}
