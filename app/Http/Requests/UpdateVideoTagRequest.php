<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVideoTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'name' => 'required|string|unique:video_tags,name,' . $id . ',video_tag_id',
            'slug' => 'required|string|unique:video_tags,slug,' . $id . ',video_tag_id',
            'description' => 'nullable|string',
        ];
    }
}
