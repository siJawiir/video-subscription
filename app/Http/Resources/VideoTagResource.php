<?php

namespace App\Http\Resources;

class VideoTagResource extends BaseResource
{
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'video_tag_id' => $this->video_tag_id,
            'name'         => $this->name,
            'slug'         => $this->slug,
            'description'  => $this->description,
        ]);
    }
}
