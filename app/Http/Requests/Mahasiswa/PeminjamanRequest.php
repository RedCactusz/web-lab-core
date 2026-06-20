<?php

namespace App\Http\Requests\Mahasiswa;

use App\Constant\Peminjaman\PeminjamanStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PeminjamanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'nama_alat' => ['required', 'string', 'max:255'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'tanggal_pengajuan' => ['nullable', 'date'],
            'tanggal_pinjam' => ['nullable', 'date'],
            'tanggal_kembali' => ['nullable', 'date'],
            'keterangan' => ['nullable', 'string'],
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['status'] = ['nullable', Rule::in(PeminjamanStatus::all())];
        }

        return $rules;
    }
}
