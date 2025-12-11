<?php

namespace App\Http\Controllers\Api;

use App\Enums\CartStatus;
use App\Enums\OrderStatus;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\AddCartItemRequest;
use App\Http\Requests\RemoveCartItemRequest;
use App\Http\Resources\CartResource;
use App\Http\Resources\CartItemResource;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Video;
use Illuminate\Support\Facades\DB;
use App\Enums\HttpStatus;

class CartController extends BaseController
{
    public function show(Request $request)
    {
        $search = $request->query('search');

        $cart = Cart::where('user_id', $request->user()->user_id)
            ->where('status', CartStatus::Active->value)
            ->with([
                'items' => fn($q) => $search ? $q->whereHas('video', fn($v) => $v->where('title', 'like', "%{$search}%")) : $q,
                'items.video'
            ])
            ->first();

        if (!$cart) {
            return $this->successResponse(null, 'Active cart not found');
        }

        $cart->load([
            'items' => fn($q) => $q->orderByDesc('created_at'),
            'items.video'
        ]);

        return $this->successResponse(
            new CartResource($cart),
            'Active cart retrieved successfully'
        );
    }



    public function addItem(AddCartItemRequest $request)
    {
        $cart = Cart::firstOrCreate(
            ['user_id' => $request->user()->user_id, 'status' => CartStatus::Active->value]
        );

        $video = Video::findOrFail($request->video_id);

        $cartItems = $cart->items->keyBy('video_id');

        if (isset($cartItems[$video->video_id])) {
            $cartItem = $cartItems[$video->video_id];
            $cartItem->increment('duration_seconds', $request->duration_seconds);
            $cartItem->increment('price', $video->price * $request->duration_seconds);
            $cartItem->refresh();
        } else {
            $cartItem = $cart->items()->create([
                'video_id' => $video->video_id,
                'duration_seconds' => $request->duration_seconds,
                'price' => $video->price * $request->duration_seconds,
            ]);
        }

        return $this->successResponse(
            new CartItemResource($cartItem->load('video')),
            'Item added to cart successfully'
        );
    }


    public function updateItem(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,cart_item_id',
            'duration_seconds' => 'required|integer|min:1',
        ]);

        $cart = Cart::where('user_id', $request->user()->user_id)
            ->where('status', CartStatus::Active->value)
            ->firstOrFail();

        $cartItem = $cart->items()->where('cart_item_id', $request->cart_item_id)->firstOrFail();

        $cartItem->update([
            'duration_seconds' => $request->duration_seconds,
            'price' => $request->duration_seconds * $cartItem->video->price,
        ]);

        return $this->successResponse(
            new CartItemResource($cartItem->load('video')),
            'Cart item updated successfully'
        );
    }



    public function removeItem(RemoveCartItemRequest $request)
    {
        $cart = $request->user()->carts()
            ->where('status', CartStatus::Active->value)
            ->firstOrFail();

        $cartItem = $cart->items()->findOrFail($request->cart_item_id);
        $cartItem->delete();

        return $this->successResponse(null, 'Item removed from cart successfully');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'cart_item_ids' => 'required|array|min:1',
            'cart_item_ids.*' => 'exists:cart_items,cart_item_id',
        ]);

        $cart = Cart::where('user_id', $request->user()->user_id)
            ->where('status', CartStatus::Active->value)
            ->with('items.video')
            ->firstOrFail();

        $selectedItems = $cart->items->whereIn('cart_item_id', $request->cart_item_ids);

        if ($selectedItems->isEmpty()) {
            return $this->errorResponse('Selected cart items not found', null, HttpStatus::BAD_REQUEST->value);
        }

        $order = DB::transaction(function () use ($cart, $selectedItems, $request) {
            $totalAmount = $selectedItems->sum(function ($item) {
                return $item->duration_seconds * $item->video->price;
            });

            $order = $request->user()->orders()->create([
                'status' => OrderStatus::Pending->value,
                'total_amount' => $totalAmount,
            ]);

            foreach ($selectedItems as $item) {
                $order->items()->create([
                    'video_id' => $item->video_id,
                    'duration_seconds' => $item->duration_seconds,
                    'price' => $item->duration_seconds * $item->video->price,
                ]);

                $item->delete();
            }

            if ($cart->items()->count() === 0) {
                $cart->update(['status' => CartStatus::CheckedOut->value]);
            }

            return $order;
        });

        return $this->successResponse(
            new OrderResource($order->load('items.video')),
            'Selected items checked out successfully',
            HttpStatus::CREATED->value
        );
    }
}
