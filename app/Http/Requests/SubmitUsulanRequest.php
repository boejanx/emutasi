<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitUsulanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Parameter dilarang keras, hanya boleh bypass via ID di controller
        // Submit hanya mengeksekusi validasi tanpa menerima mutasi data dari request
        return [];
    }
}
