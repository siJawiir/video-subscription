<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Enums\VideoAccessStatus;
use App\Http\Controllers\Api\BaseController;
use App\Models\Order;
use App\Models\Video;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Enums\HttpStatus;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\DB;

class OrderController extends BaseController
{
    public function index(Request $request)
    {
        $search     = $request->query('search');
        $status     = $request->query('status');
        $startDate  = $request->query('start_date');
        $endDate    = $request->query('end_date');

        $query = Order::query()
            ->with([
                'items' => function ($q) use ($search) {
                    $q->when($search, function ($q) use ($search) {
                        $q->whereHas('video', function ($q) use ($search) {
                            $q->where('title', 'like', "%{$search}%");
                        });
                    });
                },
                'items.video' => function ($q) use ($search) {
                    $q->when($search, function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                    });
                }
            ])

            ->when($search, function ($query) use ($search) {
                $query->whereHas('items.video', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                });
            })

            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })

            ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when($startDate && !$endDate, function ($q) use ($startDate) {
                $q->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate && !$startDate, function ($q) use ($endDate) {
                $q->whereDate('created_at', '<=', $endDate);
            })

            ->orderByDesc('created_at');

        return $this->paginatedResponse($query, $request, OrderResource::class);
    }

    public function popularVideos(Request $request)
    {
        $oneWeekAgo = now()->subDays(7);

        $popular = OrderItem::query()
            ->whereHas('order', function ($q) use ($oneWeekAgo) {
                $q->where('status', OrderStatus::Approved->value)
                    ->where('created_at', '>=', $oneWeekAgo);
            })
            ->select('video_id', DB::raw('COUNT(*) as order_count'))
            ->groupBy('video_id')
            ->orderByDesc('order_count')
            ->with(['video.categories', 'video.tags'])
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $video = $item->video;

                return [
                    'video_id'      => $video->video_id,
                    'title'         => $video->title,
                    'description'   => $video->description,
                    'video_url'     => $video->video_url,
                    'thumbnail_url' => $video->thumbnail_url ?? null,
                    'price'         => $video->price,
                    'categories'    => $video->categories,
                    'tags'          => $video->tags,
                    'order_count'   => $item->order_count,
                ];
            });

        return $this->successResponse($popular, "Popular videos last week");
    }

    public function popularVideoCategories(Request $request)
    {
        $oneWeekAgo = now()->subDays(7);

        $categories = DB::table('order_items')
            ->join('orders', 'orders.order_id', '=', 'order_items.order_id')
            ->join('video_category_map', 'video_category_map.video_id', '=', 'order_items.video_id')
            ->join('video_categories', 'video_categories.video_category_id', '=', 'video_category_map.video_category_id')
            ->where('orders.status', OrderStatus::Approved->value)
            ->where('orders.created_at', '>=', $oneWeekAgo)
            ->select(
                'video_categories.video_category_id',
                'video_categories.name',
                'video_categories.description',
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy('video_categories.video_category_id', 'video_categories.name', 'video_categories.description')
            ->orderByDesc('order_count')
            ->limit(10)
            ->get();

        return $this->successResponse($categories, "Popular categories last week");
    }


    public function show($id)
    {
        $order = Order::with('items.video', 'user')->find($id);

        if (!$order) {
            return $this->errorResponse('Order not found', null, HttpStatus::NOT_FOUND->value);
        }

        return $this->successResponse(
            new OrderResource($order),
            'Order details retrieved successfully'
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'video_id' => 'required|exists:videos,video_id',
            'duration_seconds' => 'required|integer|min:1',
        ]);

        $user = $request->user();
        $video = Video::findOrFail($request->video_id);
        $price = $video->price * $request->duration_seconds;

        $order = DB::transaction(function () use ($user, $video, $request, $price) {
            $order = $user->orders()->create([
                'status' => OrderStatus::Pending->value,
                'total_amount' => $price,
            ]);

            $order->items()->create([
                'video_id' => $video->video_id,
                'duration_seconds' => $request->duration_seconds,
                'price' => $price,
            ]);

            return $order;
        });

        return $this->successResponse(
            new OrderResource($order->load('items.video')),
            'Order created successfully',
            HttpStatus::CREATED->value
        );
    }


    public function approve($id)
    {
        $order = Order::with('items.videoAccess', 'items.video')->find($id);

        if (!$order) {
            return $this->errorResponse('Order not found', null, HttpStatus::NOT_FOUND->value);
        }

        if ($order->status != OrderStatus::Pending->value) {
            return $this->errorResponse('Order already processed', null, HttpStatus::BAD_REQUEST->value);
        }

        DB::transaction(function () use ($order) {
            $order->update(['status' => OrderStatus::Approved->value]);
            $existingAccess = $order->user->videoAccesses()
                ->whereIn('video_id', $order->items->pluck('video_id'))
                // ->where('status', VideoAccessStatus::Active->value)
                ->get()
                ->keyBy('video_id');

            foreach ($order->items as $item) {
                if (isset($existingAccess[$item->video_id])) {
                    $access = $existingAccess[$item->video_id];
                    $access->increment('purchased_time_seconds', $item->duration_seconds);
                    $access->increment('remaining_time_seconds', $item->duration_seconds);
                    $access->update([
                        'status' => VideoAccessStatus::Active->value, // reactivate if was blocked/expired
                    ]);
                } else {
                    $item->videoAccess()->create([
                        'user_id' => $order->user_id,
                        'video_id' => $item->video_id,
                        'order_item_id' => $item->order_item_id,
                        'purchased_time_seconds' => $item->duration_seconds,
                        'used_time_seconds' => 0,
                        'remaining_time_seconds' => $item->duration_seconds,
                        'status' => VideoAccessStatus::Active->value,
                        'activated_at' => now(),
                    ]);
                }
            }
        });

        return $this->successResponse(
            new OrderResource($order->fresh('items.videoAccess', 'items.video')),
            'Order approved successfully'
        );
    }



    public function reject($id)
    {
        $order = Order::with('items.video')->find($id);

        if (!$order) {
            return $this->errorResponse('Order not found', null, HttpStatus::NOT_FOUND->value);
        }

        if ($order->status != OrderStatus::Pending->value) {
            return $this->errorResponse('Order already processed', null, HttpStatus::BAD_REQUEST->value);
        }

        $order->update(['status' => OrderStatus::Rejected->value]);

        return $this->successResponse(
            new OrderResource($order->fresh('items.video')),
            'Order rejected successfully'
        );
    }
}
