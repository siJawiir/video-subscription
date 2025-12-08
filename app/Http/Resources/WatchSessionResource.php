<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WatchSessionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'video_access_id' => $this->video_access_id,
            'started_at'      => $this->started_at,
            'ended_at'        => $this->ended_at,
            'watched_seconds' => $this->watched_seconds,
            'device'          => $this->device,
            'ip_address'      => $this->ip_address,
        ];
    }
}
