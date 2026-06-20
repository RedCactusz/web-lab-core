<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MahasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $mahasiswaId = $this->route('id') ?? $this->route('mahasiswa');

        return [
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users')->ignore($mahasiswaId)],
            'password' => [$this->isMethod('post') ? 'required' : 'nullable', 'string', 'min:8'],
            'nim' => ['required', 'string', 'max:255', Rule::unique('mahasiswa')->ignore($mahasiswaId)],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'angkatan' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
