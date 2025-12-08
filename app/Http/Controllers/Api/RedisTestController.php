<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Redis;
use App\Enums\HttpStatus;

class RedisTestController extends BaseController
{
    public function test()
    {
        try {
            Redis::set('redis_test_key', 'ok');
            $value = Redis::get('redis_test_key');

            return $this->successResponse(
                ['test_value' => $value],
                'Redis connected successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Redis connection failed',
                ['error' => $e->getMessage()],
                HttpStatus::INTERNAL_SERVER_ERROR->value
            );
        }
    }
}
