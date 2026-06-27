<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class RegisterMahasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nim' => 'required|string|unique:mahasiswa,nim',
            'nama_lengkap' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ];
    }
}
