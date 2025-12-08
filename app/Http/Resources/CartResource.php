<?php

namespace App\Http\Resources;

class CartResource extends BaseResource
{
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'cart_id' => $this->cart_id,
            'user_id' => $this->user_id,
            'status'  => $this->status,
            'items'   => $this->whenLoaded('items')
                ? $this->items->map(fn($item) => new CartItemResource($item, false))
                : [],
        ]);
    }
}
