<?php

namespace App\Models;

class OrderItem extends BaseModel
{
    protected $primaryKey = 'order_item_id';
    protected $table = 'order_items';
    public $timestamps = true;

    protected $fillable = [
        'order_id',
        'video_id',
        'duration_seconds',
        'price'
    ];

    public function casts(): array
    {
        return [
            'duration_seconds' => 'integer',
            'price' => 'decimal:2',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function video()
    {
        return $this->belongsTo(Video::class, 'video_id', 'video_id');
    }

    public function videoAccess()
    {
        return $this->hasOne(VideoAccess::class, 'order_item_id', 'order_item_id');
    }
}
