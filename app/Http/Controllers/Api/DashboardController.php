<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Order;
use App\Enums\HttpStatus;
use App\Http\Resources\OrderResource;

class DashboardController extends BaseController
{

    public function stats()
    {
        $totalVideos = Video::count();

        $pendingOrders = Order::where('status', 'pending')->count();
        $approvedOrders = Order::where('status', 'approved')->count();
        $rejectedOrders = Order::where('status', 'rejected')->count();

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
