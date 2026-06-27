<?php

namespace App\Http\Requests\SuperAdmin;

use App\Entities\Pengajar;
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
        $pengajarId = $this->route('pengajar');
        $userId = $pengajarId ? Pengajar::find($pengajarId)?->user_id : null;

        return [
            'nama_lengkap' => ['required', 'string'],
            'nip' => ['nullable', 'string', Rule::unique('pengajar')->ignore($pengajarId)],
            'username' => ['required', 'string', Rule::unique('users')->ignore($userId)],
            'password' => [$this->isMethod('post') ? 'required' : 'nullable', 'string', 'min:6'],
            'praktikum' => ['nullable', 'string'],
            'plug' => ['nullable', 'array'],
            'is_active' => ['boolean'],
        ];
    }
}
