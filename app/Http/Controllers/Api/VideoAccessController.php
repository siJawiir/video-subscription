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
        $search     = $request->query('search');
        $status     = $request->query('status');

        $query = VideoAccess::query()
            ->where('user_id', $request->user()->user_id)
            ->with('video')

            ->when($search, function ($q) use ($search) {
                $q->whereHas(
                    'video',
                    fn($v) =>
                    $v->where('title', 'like', "%{$search}%")
                );
            })

            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })

            ->orderByDesc('activated_at');

        return $this->paginatedResponse(
            $query,
            $request,
            VideoAccessResource::class
        );
    }

    public function show(Request $request, $id)
    {
        $videoAccess = VideoAccess::with('video')
            ->where('user_id', $request->user()->user_id)
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
