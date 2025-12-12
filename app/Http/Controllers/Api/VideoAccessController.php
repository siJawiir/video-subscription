<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Models\VideoAccess;
use App\Enums\VideoAccessStatus;
use App\Enums\HttpStatus;
use App\Http\Resources\VideoAccessResource;

class VideoAccessController extends BaseController
{
    public function index(Request $request)
    {
        $search      = $request->query('search');
        $categoryId  = $request->query('category_id');
        $tagId       = $request->query('tag_id');

        $currentPage = (int) $request->query('current_page', 1);
        $perPage     = (int) $request->query('per_page', 10);
        $sortBy      = $request->query('sort_by', 'activated_at');
        $orderBy     = $request->query('order_by', 'desc');

        if (!in_array(strtolower($orderBy), ['asc', 'desc'])) {
            $orderBy = 'desc';
        }

        $allowedSort = ['activated_at', 'created_at', 'updated_at'];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'activated_at';
        }

        $query = VideoAccess::query()
            ->where('user_id', $request->user()->user_id)
            ->where('status', VideoAccessStatus::Active->value)
            ->with(['video.categories', 'video.tags'])

            ->when($search, function ($q) use ($search) {
                $q->whereHas('video', function ($v) use ($search) {
                    $v->where('title', 'like', "%{$search}%");
                });
            })

            ->when($categoryId, function ($q) use ($categoryId) {
                $q->whereHas('video.categories', function ($sub) use ($categoryId) {
                    $sub->where('video_categories.video_category_id', $categoryId);
                });
            })

            ->when($tagId, function ($q) use ($tagId) {
                $q->whereHas('video.tags', function ($sub) use ($tagId) {
                    $sub->where('video_tags.video_tag_id', $tagId);
                });
            })

            ->orderBy($sortBy, $orderBy);

        $request->merge([
            'page'    => $currentPage,
            'perPage' => $perPage,
        ]);

        return $this->paginatedResponse($query, $request, VideoAccessResource::class);
    }


    public function show(Request $request, $id)
    {
        $videoAccess = VideoAccess::with('video')
            ->where('user_id', $request->user()->user_id)
            ->where('status', VideoAccessStatus::Active->value)
            ->find($id);

        if (!$videoAccess) {
            return $this->errorResponse(
                'Video access not found',
                null,
                HttpStatus::NOT_FOUND->value
            );
        }

        return $this->successResponse(
            new VideoAccessResource($videoAccess),
            'Video access details retrieved successfully'
        );
    }

    public function block($id)
    {
        $videoAccess = VideoAccess::find($id);

        if (!$videoAccess) {
            return $this->errorResponse(
                'Video access not found',
                null,
                HttpStatus::NOT_FOUND->value
            );
        }

        $videoAccess->update([
            'status' => VideoAccessStatus::Blocked->value,
        ]);

        return $this->successResponse(
            new VideoAccessResource($videoAccess->load('video')),
            'Video access blocked successfully'
        );
    }
}
