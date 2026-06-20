<?php

namespace App\Http\Requests\Admin;

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
        $praktikumId = $this->route('id') ?? $this->route('praktikum');

        return [
            'kode' => ['required', 'string', 'max:50', Rule::unique('praktikum')->ignore($praktikumId)],
            'nama' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('praktikum')->ignore($praktikumId)],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
