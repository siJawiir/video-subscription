<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVideoCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'name' => 'required|string|unique:video_categories,name,' . $id . ',video_category_id',
            'slug' => 'required|string|unique:video_categories,slug,' . $id . ',video_category_id',
            'description' => 'nullable|string',
        ];
    }
}
