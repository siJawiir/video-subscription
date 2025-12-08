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
        $query = VideoTag::query()
            ->when($request->filled('search'), fn($q) => $q->where('name', 'like', "%{$request->search}%"));

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
