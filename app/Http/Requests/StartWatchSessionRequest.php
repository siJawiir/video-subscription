<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StartWatchSessionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'video_access_id' => 'required|exists:video_access,video_access_id',
        ];
    }
}
