<?php

namespace App\Http\Requests\SuperAdmin;

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
        $mahasiswaId = $this->route('mahasiswa');

        return [
            'nim' => ['required', 'string', Rule::unique('mahasiswa')->ignore($mahasiswaId)],
            'nama_lengkap' => ['required', 'string'],
            'password' => [$this->isMethod('post') ? 'required' : 'nullable', 'string', 'min:6'],
            'is_active' => ['boolean'],
        ];
    }
}
