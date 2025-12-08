<?php

namespace App\Http\Resources;

class VideoAccessResource extends BaseResource
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
            'video_access_id'        => $this->video_access_id,
            'user_id'                => $this->user_id,
            'video_id'               => $this->video_id,
            'order_item_id'          => $this->order_item_id,
            'purchased_time_seconds' => $this->purchased_time_seconds,
            'used_time_seconds'      => $this->used_time_seconds,
            'remaining_time_seconds' => $this->remaining_time_seconds,
            'status'                 => $this->status,
            'activated_at'           => $this->activated_at?->toDateTimeString(),
        ];

        if ($this->relationLoaded('video')) {
            $data['video'] = new VideoResource($this->video, false);
        }

        if ($this->withAudit) {
            $data = array_merge($data, parent::toArray($request));
        }

        return $data;
    }
}
