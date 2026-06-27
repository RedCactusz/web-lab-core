<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InventarisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $inventarisId = $this->route('inventari');

        return [
            'kode_alat' => ['required', 'string', Rule::unique('inventaris')->ignore($inventarisId)],
            'nama' => ['required', 'string'],
            'kategori' => ['required', 'in:surveying,aksesoris,perlengkapan,lainnya'],
            'merk' => ['nullable', 'string'],
            'tipe' => ['nullable', 'string'],
            'kondisi' => ['required', 'in:baik,rusak_ringan,rusak_berat,maintenance'],
            'jumlah' => ['required', 'integer', 'min:0'],
            'lokasi' => ['nullable', 'string'],
            'keterangan' => ['nullable', 'string'],
            'foto' => ['nullable', 'array'],
        ];
    }
}
