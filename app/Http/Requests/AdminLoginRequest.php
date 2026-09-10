<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nomor_induk' => ['required', 'integer'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nomor_induk.required' => 'Nomor induk (NIP/NIM) wajib diisi.',
            'nomor_induk.integer' => 'Nomor induk harus berupa angka.',
            'password.required' => 'Password wajib diisi.',
        ];
    }
}
