<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PraktikumRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $praktikumId = $this->route('praktikum');

        return [
            'kode' => ['required', 'string', Rule::unique('praktikum')->ignore($praktikumId)],
            'nama' => ['required', 'string'],
            'slug' => ['required', 'string', Rule::unique('praktikum')->ignore($praktikumId)],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'jumlah_plug' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
