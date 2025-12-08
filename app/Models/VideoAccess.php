<?php

namespace App\Models;
use App\Models\BaseModel;

class VideoAccess extends BaseModel
{
    protected $primaryKey = 'video_access_id';
    protected $table = 'video_access';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'video_id',
        'order_item_id',
        'purchased_time_seconds',
        'used_time_seconds',
        'remaining_time_seconds',
        'status',
        'activated_at',
    ];

    public function casts(): array
    {
        return [
            'purchased_time_seconds' => 'integer',
            'used_time_seconds' => 'integer',
            'remaining_time_seconds' => 'integer',
            'status' => 'integer',
            'activated_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function video()
    {
        return $this->belongsTo(Video::class, 'video_id', 'video_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id', 'order_item_id');
    }

    public function watchSessions()
    {
        return $this->hasMany(WatchSession::class, 'video_access_id', 'video_access_id');
    }
}
