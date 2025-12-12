<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Order;
use App\Enums\HttpStatus;
use App\Enums\OrderStatus;
use App\Models\Video;

class DashboardController extends BaseController
{

    public function stats()
    {
        $totalVideos = Video::count();

        $pendingOrders = Order::where('status',OrderStatus::Pending->value )->count();
        $approvedOrders = Order::where('status', OrderStatus::Approved->value)->count();
        $rejectedOrders = Order::where('status', OrderStatus::Rejected->value)->count();

        $payload = [
            'total_videos' => $totalVideos,
            'pending_orders' => $pendingOrders,
            'approved_orders' => $approvedOrders,
            'rejected_orders' => $rejectedOrders,
        ];

        return $this->successResponse(
            $payload,
            'Dashboard stats retrieved successfully',
            HttpStatus::OK->value
        );
    }
}
