<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class PeminjamanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nim' => ['required', 'exists:mahasiswa,nim'],
            'nama_mahasiswa' => ['required', 'string'],
            'keperluan' => ['required', 'string'],
            'alasan_lainnya' => ['nullable', 'string'],
            'tanggal_pinjam' => ['required', 'date'],
            'jam_pinjam' => ['required', 'string'],
            'tanggal_kembali' => ['required', 'date', 'after_or_equal:tanggal_pinjam'],
            'jam_kembali' => ['required', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.nama_alat' => ['required', 'string'],
            'items.*.jumlah' => ['required', 'integer', 'min:1'],
        ];
    }
}
