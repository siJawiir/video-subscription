<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\VideoTag;
use App\Http\Requests\StoreVideoTagRequest;
use App\Http\Requests\UpdateVideoTagRequest;
use App\Http\Resources\VideoTagResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Enums\HttpStatus;

class VideoTagController extends BaseController
{
    public function index(Request $request)
    {
        $currentPage = (int) $request->query('current_page', 1);
        $perPage     = (int) $request->query('per_page', 10);
        $sortBy      = $request->query('sort_by', 'created_at');
        $orderBy     = $request->query('order_by', 'desc');
        $search      = $request->query('search');

        if (!in_array(strtolower($orderBy), ['asc', 'desc'])) {
            $orderBy = 'desc';
        }

        $allowedSort = ['name', 'created_at', 'updated_at'];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'created_at';
        }

        $query = VideoTag::query()
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orderBy($sortBy, $orderBy);

        $request->merge([
            'page'    => $currentPage,
            'perPage' => $perPage,
        ]);

        return $this->paginatedResponse($query, $request, VideoTagResource::class);
    }


    public function show(int $id)
    {
        $tag = VideoTag::findOrFail($id);

        return $this->successResponse(
            new VideoTagResource($tag),
            'Video tag retrieved successfully'
        );
    }

    public function store(StoreVideoTagRequest $request)
    {
        $tag = DB::transaction(function () use ($request) {
            return VideoTag::create($request->validated());
        });

        return $this->successResponse(
            new VideoTagResource($tag),
            'Video tag created successfully',
            HttpStatus::CREATED->value
        );
    }

    public function update(UpdateVideoTagRequest $request, int $id)
    {
        $tag = VideoTag::findOrFail($id);

        DB::transaction(function () use ($request, $tag) {
            $tag->update($request->validated());
        });

        return $this->successResponse(
            new VideoTagResource($tag->fresh()),
            'Video tag updated successfully'
        );
    }

    public function destroy(int $id)
    {
        $tag = VideoTag::findOrFail($id);

        $tag->delete();

        return $this->successResponse(
            null,
            'Video tag deleted successfully'
        );
    }
}
