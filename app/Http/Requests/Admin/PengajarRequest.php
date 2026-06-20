<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PengajarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pengajarId = $this->route('id') ?? $this->route('pengajar');

        return [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($pengajarId)],
            'password' => [$this->isMethod('post') ? 'required' : 'nullable', 'string', 'min:8'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:255', Rule::unique('pengajar')->ignore($pengajarId)],
            'praktikum_slug' => ['nullable', 'exists:praktikum,slug'],
            'plug' => ['nullable', 'array'],
            'plug.*' => ['integer'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
