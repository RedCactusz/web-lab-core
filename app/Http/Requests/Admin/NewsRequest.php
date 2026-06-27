<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
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
            'category' => ['required', 'string'],
            'image' => ['nullable', 'string'],
            'is_published' => ['boolean'],
        ];
    }
}
