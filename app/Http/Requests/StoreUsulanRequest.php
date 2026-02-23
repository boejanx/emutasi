<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUsulanRequest extends FormRequest
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
            'no_surat' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'perihal' => 'required|string',
            'details' => 'required|array|min:1',
            'details.*.nip' => 'required|string|max:255',
            'details.*.nama' => 'required|string|max:255',
            'details.*.jabatan' => 'required|string|max:255',
            'details.*.lokasi_awal' => 'required|string|max:255',
            'details.*.lokasi_tujuan' => 'required|string|max:255',
            'details.*.catatan' => 'nullable|string',
            'details.*.berkas' => 'nullable|array',
            'details.*.berkas.*.path_dokumen' => 'required|string',
            'details.*.berkas.*.id_dokumen' => 'nullable|string',
        ];
    }
}
