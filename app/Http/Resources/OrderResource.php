<?php

namespace App\Http\Resources;

class OrderResource extends BaseResource
{
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'order_id' => $this->order_id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'total_amount' => $this->total_amount,
            'items'   => $this->whenLoaded('items')
                ? $this->items->map(fn($item) => new OrderItemResource($item, false))
                : [],
        ]);
    }
}
