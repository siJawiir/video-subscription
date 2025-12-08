<?php

namespace App\Http\Resources;

class VideoCategoryResource extends BaseResource
{
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'video_category_id' => $this->video_category_id,
            'name'              => $this->name,
            'slug'              => $this->slug,
            'description'       => $this->description,
        ]);
    }
}
