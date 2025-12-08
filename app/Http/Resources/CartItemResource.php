<?php

namespace App\Http\Resources;

class CartItemResource extends BaseResource
{
    protected bool $withAudit;

    public function __construct($resource, bool $withAudit = true)
    {
        parent::__construct($resource);
        $this->withAudit = $withAudit;
    }

    public function toArray($request): array
    {
        $data = [
            'cart_item_id'      => $this->cart_item_id,
            'video_id'          => $this->video_id,
            'duration_seconds'  => $this->duration_seconds,
            'price'             => $this->price,
            'video'             => new VideoResource($this->whenLoaded('video'), false),
        ];

        if ($this->withAudit) {
            $data = array_merge($data, parent::toArray($request));
        }

        return $data;
    }
}
