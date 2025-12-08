<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function profile(Request $request)
    {
        return $this->successResponse(
            $request->user(),
            'User profile retrieved successfully'
        );
    }
}
