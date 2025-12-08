<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVideoRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'video_url' => 'sometimes|required|url',
            'price' => 'sometimes|required|numeric|min:0',
            'is_active' => 'sometimes|required|boolean',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:video_categories,video_category_id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:video_tags,video_tag_id',
        ];
    }
}
