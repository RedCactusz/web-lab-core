<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'image' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'is_published' => ['boolean'],
        ];
    }
}
