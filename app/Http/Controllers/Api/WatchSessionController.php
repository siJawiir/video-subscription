<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Redis;
use App\Models\VideoAccess;
use App\Models\WatchSession;
use App\Enums\VideoAccessStatus;
use App\Enums\HttpStatus;
use App\Http\Requests\StartWatchSessionRequest;
use App\Http\Requests\PingWatchSessionRequest;
use App\Http\Requests\EndWatchSessionRequest;
use App\Http\Resources\WatchSessionResource;

class WatchSessionController extends BaseController
{
    public function start(StartWatchSessionRequest $request)
    {
        $user = $request->user();
        $videoAccess = VideoAccess::where('user_id', $user->user_id)
            ->where('video_access_id', $request->video_access_id)
            ->where('status', VideoAccessStatus::Active->value)
            ->first();

        if (!$videoAccess) {
            return $this->errorResponse('Video access not found or inactive', null, HttpStatus::NOT_FOUND->value);
        }

        $redisKey = "watch:{$user->user_id}:{$videoAccess->video_access_id}";

        if (Redis::exists($redisKey)) {
            return $this->errorResponse('Video already started', null, HttpStatus::BAD_REQUEST->value);
        }

        Redis::hmset($redisKey, [
            'started_at' => now()->timestamp,
            'watched_seconds' => 0,
            'last_ping' => now()->timestamp,
        ]);

        Redis::expire($redisKey, $videoAccess->remaining_time_seconds);

        return $this->successResponse(null, 'Watch session started successfully');
    }

    public function ping(PingWatchSessionRequest $request)
    {
        $user = $request->user();
        $videoAccess = VideoAccess::where('user_id', $user->user_id)
            ->where('video_access_id', $request->video_access_id)
            ->first();

        if (!$videoAccess) {
            return $this->errorResponse('Video access not found', null, HttpStatus::NOT_FOUND->value);
        }

        $redisKey = "watch:{$user->user_id}:{$videoAccess->video_access_id}";

        if (!Redis::exists($redisKey)) {
            return $this->errorResponse('Watch session not started', null, HttpStatus::BAD_REQUEST->value);
        }

        $data = Redis::hgetall($redisKey);
        $now = now()->timestamp;
        $delta = $now - $data['last_ping'];
        $watched = $data['watched_seconds'] + $delta;

        if ($watched >= $videoAccess->remaining_time_seconds) {
            $watched = $videoAccess->remaining_time_seconds;
            $session = $this->endSession($user->user_id, $videoAccess->video_access_id, $watched);

            return $this->successResponse(
                new WatchSessionResource($session),
                'Time expired for this video'
            );
        }

        Redis::hmset($redisKey, [
            'watched_seconds' => $watched,
            'last_ping' => $now,
        ]);

        return $this->successResponse(
            ['watched_seconds' => $watched],
            'Watch session updated'
        );
    }

    public function end(EndWatchSessionRequest $request)
    {
        $user = $request->user();
        $videoAccess = VideoAccess::where('user_id', $user->user_id)
            ->where('video_access_id', $request->video_access_id)
            ->first();

        if (!$videoAccess) {
            return $this->errorResponse('Video access not found', null, HttpStatus::NOT_FOUND->value);
        }

        $redisKey = "watch:{$user->user_id}:{$videoAccess->video_access_id}";

        if (!Redis::exists($redisKey)) {
            return $this->errorResponse('Watch session not started', null, HttpStatus::BAD_REQUEST->value);
        }

        $data = Redis::hgetall($redisKey);
        $watched = $data['watched_seconds'] + (now()->timestamp - $data['last_ping']);

        $session = $this->endSession($user->user_id, $videoAccess->video_access_id, $watched);

        return $this->successResponse(
            new WatchSessionResource($session),
            'Watch session ended successfully'
        );
    }

    private function endSession($user_id, $video_access_id, $watched_seconds)
    {
        $videoAccess = VideoAccess::find($video_access_id);

        $videoAccess->used_time_seconds += $watched_seconds;
        $videoAccess->remaining_time_seconds -= $watched_seconds;

        if ($videoAccess->remaining_time_seconds <= 0) {
            $videoAccess->remaining_time_seconds = 0;
            $videoAccess->status = VideoAccessStatus::Expired->value;
        }

        $videoAccess->save();

        $session = WatchSession::create([
            'video_access_id' => $video_access_id,
            'started_at' => now()->subSeconds($watched_seconds),
            'ended_at' => now(),
            'watched_seconds' => $watched_seconds,
            'device' => request()->header('User-Agent'),
            'ip_address' => request()->ip(),
        ]);

        Redis::del("watch:{$user_id}:{$video_access_id}");

        return $session;
    }
}
