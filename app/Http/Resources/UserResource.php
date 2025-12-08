<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'       => $this->user_id,
            'username' => $this->username,
            'role'     => $this->role,
        ];
    }
}
