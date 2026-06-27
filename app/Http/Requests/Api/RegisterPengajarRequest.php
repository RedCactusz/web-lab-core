<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class RegisterPengajarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'praktikum' => 'required|string|exists:praktikum,slug',
            'nip' => 'nullable|string|max:255',
            'plug' => 'nullable|array',
            'plug.*' => 'integer|min:1',
        ];
    }
}
