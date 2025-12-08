<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVideoTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:video_tags,name',
            'slug' => 'required|string|unique:video_tags,slug',
            'description' => 'nullable|string',
        ];
    }
}
