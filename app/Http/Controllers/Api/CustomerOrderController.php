<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use App\Enums\HttpStatus;

class CustomerOrderController extends BaseController
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $ordersQuery = Order::with('items.video')
            ->where('user_id', $request->user()->user_id)
            ->when($search, fn($q) => $q->whereHas(
                'items.video',
                fn($q2) =>
                $q2->where('title', 'like', "%{$search}%")
            ))
            ->orderByDesc('created_at');

        return $this->paginatedResponse($ordersQuery, $request, OrderResource::class);
    }


    public function show(Request $request, $id)
    {
        $order = Order::with('items.video')
            ->where('user_id', $request->user()->user_id)
            ->find($id);

        if (!$order) {
            return $this->errorResponse('Order not found', null, HttpStatus::NOT_FOUND->value);
        }

        return $this->successResponse(
            new OrderResource($order),
            'Order details retrieved successfully'
        );
    }
}
