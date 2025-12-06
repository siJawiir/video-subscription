<?php

namespace App\Models;


class WatchSession extends BaseModel
{
    protected $primaryKey = 'watch_session_id';
    protected $table = 'watch_sessions';
    public $timestamps = true;

    protected $fillable = [
        'video_access_id',
        'started_at',
        'ended_at',
        'watched_seconds',
        'device',
        'ip_address',
    ];

    public function casts(): array
    {
        return [
            'watched_seconds' => 'integer',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    public function videoAccess()
    {
        return $this->belongsTo(VideoAccess::class, 'video_access_id', 'video_access_id');
    }
}
