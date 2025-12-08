<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'video_id' => 'required|exists:videos,video_id',
            'duration_seconds' => 'required|integer|min:1',
        ];
    }
}
