<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Video;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Http\Resources\VideoResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Enums\HttpStatus;

class VideoController extends BaseController
{
    public function index(Request $request)
    {
        $search       = $request->query('search');
        $categoryId   = $request->query('category_id');
        $tagId        = $request->query('tag_id');

        $currentPage  = (int) $request->query('current_page', 1);
        $perPage      = (int) $request->query('per_page', 10);
        $sortBy       = $request->query('sort_by', 'created_at');
        $orderBy      = $request->query('order_by', 'desc');
        
        if (!in_array(strtolower($orderBy), ['asc', 'desc'])) {
            $orderBy = 'desc';
        }

        $allowedSort = ['title', 'created_at', 'updated_at'];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'created_at';
        }

        $query = Video::query()
            ->where('is_active', 1)
            ->with(['categories', 'tags'])

            ->when($search, function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            })

            ->when($categoryId, function ($q) use ($categoryId) {
                $q->whereHas('categories', function ($sub) use ($categoryId) {
                    $sub->where('video_categories.video_category_id', $categoryId);
                });
            })

            ->when($tagId, function ($q) use ($tagId) {
                $q->whereHas('tags', function ($sub) use ($tagId) {
                    $sub->where('video_tags.video_tag_id', $tagId);
                });
            })

            ->orderBy($sortBy, $orderBy);

        $request->merge([
            'page'    => $currentPage,
            'perPage' => $perPage,
        ]);

        return $this->paginatedResponse($query, $request, VideoResource::class);
    }


    public function resources(Request $request)
    {
        $search = $request->query('search');

        $query = Video::query()
            ->where('is_active', 1)
            ->when($search, function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            })
            ->orderByDesc('created_at')
            ->limit(10);

        $videos = $query->get();

        $data = $videos->map(function ($video) {
            return [
                'value' => $video->video_id,
                'label' => $video->title,
            ];
        });

        return $this->successResponse($data, "Video resources retrieved successfully");
    }


    public function latest(Request $request)
    {
        $latestVideos = Video::query()
            ->where('is_active', 1)
            ->with(['categories', 'tags'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return $this->successResponse(
            VideoResource::collection($latestVideos),
            "Latest 10 videos retrieved successfully"
        );
    }

    public function show($id)
    {
        $video = Video::with(['categories', 'tags'])->find($id);

        if (!$video) {
            return $this->errorResponse('Video not found', null, HttpStatus::NOT_FOUND->value);
        }

        return $this->successResponse(
            new VideoResource($video),
            'Video details retrieved successfully'
        );
    }

    public function store(StoreVideoRequest $request)
    {
        $video = DB::transaction(function () use ($request) {
            $video = Video::create($request->only('title', 'description', 'video_url', 'price', 'is_active'));

            $video->categories()->sync($request->categories ?? []);
            $video->tags()->sync($request->tags ?? []);

            return $video->load(['categories', 'tags']);
        });

        return $this->successResponse(
            new VideoResource($video),
            'Video created successfully',
            HttpStatus::CREATED->value
        );
    }

    public function update(UpdateVideoRequest $request, $id)
    {
        $video = Video::find($id);

        if (!$video) {
            return $this->errorResponse('Video not found', null, HttpStatus::NOT_FOUND->value);
        }

        DB::transaction(function () use ($request, $video) {
            $video->update($request->only('title', 'description', 'video_url', 'price', 'is_active'));

            if ($request->has('categories')) {
                $video->categories()->sync($request->categories);
            }

            if ($request->has('tags')) {
                $video->tags()->sync($request->tags);
            }
        });

        return $this->successResponse(
            new VideoResource($video->fresh(['categories', 'tags'])),
            'Video updated successfully'
        );
    }

    public function destroy($id)
    {
        $video = Video::find($id);

        if (!$video) {
            return $this->errorResponse('Video not found', null, HttpStatus::NOT_FOUND->value);
        }

        $video->delete();

        return $this->successResponse(null, 'Video deleted successfully');
    }
}
