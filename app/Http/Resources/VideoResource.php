<?php

namespace App\Http\Resources;


class VideoResource extends BaseResource
{
    protected bool $withAudit = true;

    public function __construct($resource, bool $withAudit = true)
    {
        parent::__construct($resource);
        $this->withAudit = $withAudit;
    }

    public function toArray($request): array
    {
        $data = [
            'video_id'    => $this->video_id,
            'title'       => $this->title,
            'description' => $this->description,
            'video_url'   => $this->video_url,
            'price'       => $this->price,
            'is_active'   => $this->is_active,
            'categories'  => $this->categories->map(fn($c) => [
                'id' => $c->video_category_id,
                'name' => $c->name,
            ])->values(),
            'tags'        => $this->tags->map(fn($t) => [
                'id' => $t->video_tag_id,
                'name' => $t->name,
            ])->values(),
        ];

        if ($this->withAudit) {
            $data = array_merge(parent::toArray($request), $data);
        }

        return $data;
    }
}
