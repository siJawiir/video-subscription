<?php

namespace App\Models;

class CartItem extends BaseModel
{
    protected $primaryKey = 'cart_item_id';
    protected $table = 'cart_items';
    public $timestamps = true;

    protected $fillable = [
        'cart_id',
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

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'cart_id');
    }

    public function video()
    {
        return $this->belongsTo(Video::class, 'video_id', 'video_id');
    }
}
