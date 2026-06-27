<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PartnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string'],
            'logo' => ['required', 'string'],
            'website' => ['nullable', 'url'],
            'description' => ['nullable', 'string'],
            'is_published' => ['boolean'],
        ];
    }
}
