<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVideoCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:video_categories,name',
            'slug' => 'required|string|unique:video_categories,slug',
            'description' => 'nullable|string',
        ];
    }
}
