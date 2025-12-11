<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\VideoCategory;
use App\Http\Requests\StoreVideoCategoryRequest;
use App\Http\Requests\UpdateVideoCategoryRequest;
use App\Http\Resources\VideoCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Enums\HttpStatus;

class VideoCategoryController extends BaseController
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

        $query = VideoCategory::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%");
                });
            })
            ->orderBy($sortBy, $orderBy);

        $request->merge([
            'page'    => $currentPage,
            'perPage' => $perPage,
        ]);

        return $this->paginatedResponse($query, $request, VideoCategoryResource::class);
    }


    public function resources(Request $request)
    {
        $search = $request->query('search');

        $query = VideoCategory::query()
            ->where('deleted_at', null)
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orderByDesc('created_at');

        $categories = $query->get();

        $data = $categories->map(function ($category) {
            return [
                'value' => $category->video_category_id,
                'label' => $category->name,
            ];
        });

        return $this->successResponse($data, "Video category resources retrieved successfully");
    }

    public function show(int $id)
    {
        $category = VideoCategory::findOrFail($id);

        return $this->successResponse(
            new VideoCategoryResource($category),
            'Video category retrieved successfully'
        );
    }

    public function store(StoreVideoCategoryRequest $request)
    {
        $category = DB::transaction(function () use ($request) {
            return VideoCategory::create($request->validated());
        });

        return $this->successResponse(
            new VideoCategoryResource($category),
            'Video category created successfully',
            HttpStatus::CREATED->value
        );
    }

    public function update(UpdateVideoCategoryRequest $request, int $id)
    {
        $category = VideoCategory::findOrFail($id);

        DB::transaction(function () use ($request, $category) {
            $category->update($request->validated());
        });

        return $this->successResponse(
            new VideoCategoryResource($category->fresh()),
            'Video category updated successfully'
        );
    }

    public function destroy(int $id)
    {
        $category = VideoCategory::findOrFail($id);

        $category->delete();

        return $this->successResponse(
            null,
            'Video category deleted successfully'
        );
    }
}
