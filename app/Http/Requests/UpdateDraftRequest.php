<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDraftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
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
            // details update might be more complex, we'll keep it simple for now
        ];
    }
}
