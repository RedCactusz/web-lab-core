<?php

namespace App\Http\Requests\Pengajar;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kelompok' => ['nullable', 'integer'],
            'plug' => ['nullable', 'integer'],
            'nilai_harian' => ['nullable', 'array'],
            'nilai_harian.*.hadir' => ['nullable', 'in:Hadir,Izin,Sakit,Alfa'],
            'nilai_harian.*.lapangan' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai_harian.*.kuis' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai_harian.*.laporan.bab1' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai_harian.*.laporan.bab2' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai_harian.*.laporan.bab3' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai_harian.*.laporan.bab4' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai_harian.*.laporan.bab5' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
