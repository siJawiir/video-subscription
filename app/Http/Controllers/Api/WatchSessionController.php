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
    private const TTL_SECONDS = 120; 

    private function redisKey($userId, $videoAccessId)
    {
        return "watch:{$userId}:{$videoAccessId}";
    }

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

        $key = $this->redisKey($user->user_id, $videoAccess->video_access_id);

        if (Redis::exists($key)) {
            return $this->errorResponse('Video already started', null, HttpStatus::BAD_REQUEST->value);
        }

        $now = now()->timestamp;

        Redis::hmset($key, [
            'started_at' => $now,
            'watched_seconds' => 0,
            'last_ping' => $now,
        ]);

        Redis::expire($key, self::TTL_SECONDS);

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

        $key = $this->redisKey($user->user_id, $videoAccess->video_access_id);

        if (!Redis::exists($key)) {
            return $this->errorResponse('Watch session not started', null, HttpStatus::BAD_REQUEST->value);
        }

        $data = Redis::hgetall($key);

        $now = now()->timestamp;
        $delta = max(0, $now - $data['last_ping']); 
        $totalWatched = $data['watched_seconds'] + $delta;

        $watched = min($totalWatched, $videoAccess->remaining_time_seconds);

        if ($watched >= $videoAccess->remaining_time_seconds) {
            $session = $this->endSession($videoAccess, $data, $delta);

            return $this->successResponse(
                new WatchSessionResource($session),
                'Time expired for this video'
            );
        }

        Redis::hmset($key, [
            'watched_seconds' => $watched,
            'last_ping' => $now,
        ]);

        Redis::expire($key, self::TTL_SECONDS);

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

        $key = $this->redisKey($user->user_id, $videoAccess->video_access_id);

        if (!Redis::exists($key)) {
            return $this->errorResponse('Watch session not started', null, HttpStatus::BAD_REQUEST->value);
        }

        $data = Redis::hgetall($key);

        $now = now()->timestamp;
        $delta = max(0, $now - $data['last_ping']);

        $session = $this->endSession($videoAccess, $data, $delta);

        return $this->successResponse(
            new WatchSessionResource($session),
            'Watch session ended successfully'
        );
    }

    private function endSession(VideoAccess $videoAccess, array $redisData, int $delta)
    {

        $watched = min(
            $redisData['watched_seconds'] + $delta,
            $videoAccess->remaining_time_seconds
        );

        $startedAt = $redisData['started_at'];

        $videoAccess->used_time_seconds += $watched;
        $videoAccess->remaining_time_seconds = max(
            0,
            $videoAccess->remaining_time_seconds - $watched
        );

        if ($videoAccess->remaining_time_seconds <= 0) {
            $videoAccess->status = VideoAccessStatus::Expired->value;
        }

        $videoAccess->save();

        $session = WatchSession::create([
            'video_access_id' => $videoAccess->video_access_id,
            'started_at' => date('Y-m-d H:i:s', $startedAt),
            'ended_at' => now(),
            'watched_seconds' => $watched,
            'device' => request()->header('User-Agent'),
            'ip_address' => request()->ip(),
        ]);

        $key = $this->redisKey($videoAccess->user_id, $videoAccess->video_access_id);
        Redis::del($key);

        return $session;
    }
}
