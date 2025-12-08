<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Enums\HttpStatus;

class BaseController extends Controller
{
    protected function successResponse($data = null, $message = '', $code = HttpStatus::OK->value)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    protected function errorResponse($message = '', $errors = null, $code = HttpStatus::BAD_REQUEST->value)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => null,
            'errors'  => $errors,
        ], $code);
    }

    protected function paginatedResponse($query, $request, $resourceClass, $defaultPerPage = 10)
    {
        $perPage = max(1, min(100, (int)$request->get('per_page', $defaultPerPage)));

        $paginator = $query->paginate($perPage);

        $collection = $resourceClass::collection($paginator->items());

        return response()->json([
            'success' => true,
            'message' => 'List retrieved successfully',
            'data' => $collection->toArray($request),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }
}
