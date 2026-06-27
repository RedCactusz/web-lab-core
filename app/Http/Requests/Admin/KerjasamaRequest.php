<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class KerjasamaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'content' => ['required', 'string'],
            'date' => ['required', 'date'],
            'partner_id' => ['required', 'exists:partners,id'],
            'image' => ['nullable', 'string'],
            'is_published' => ['boolean'],
        ];
    }
}
