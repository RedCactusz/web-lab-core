<?php

namespace App\Http\Requests\Admin;

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
            'status' => ['required', 'in:pending,approved,decline,completed,miss'],
            'revisi_catatan' => ['nullable', 'string'],
            'revised_items' => ['nullable', 'array'],
            'pengembalian_catatan' => ['nullable', 'string'],
            'pengembalian_items' => ['nullable', 'array'],
            'tanggal_dikembalikan' => ['nullable', 'date'],
        ];
    }
}
