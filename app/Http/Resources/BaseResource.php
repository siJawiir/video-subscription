<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    public function toArray($request): array
    {
        $data = parent::toArray($request);

        return array_merge($data, [
            'created_by' => $this->created_by ?? null,
            'updated_by' => $this->updated_by ?? null,
            'deleted_by' => $this->deleted_by ?? null,
            'created_at' => isset($this->created_at) ? $this->created_at?->toDateTimeString() : null,
            'updated_at' => isset($this->updated_at) ? $this->updated_at?->toDateTimeString() : null,
            'deleted_at' => isset($this->deleted_at) ? $this->deleted_at?->toDateTimeString() : null,
        ]);
    }
}
