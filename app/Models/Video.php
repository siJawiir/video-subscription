<?php

namespace App\Models;

class Video extends BaseModel
{
    protected $primaryKey = 'video_id';
    protected $table = 'videos';
    public $timestamps = true;

    protected $fillable = [
        'title',
        'description',
        'video_url',
        'price',
        'is_active',
    ];

    public function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function categories()
    {
        return $this->belongsToMany(
            VideoCategory::class,
            'video_category_map',
            'video_id',
            'video_category_id'
        );
    }

    public function tags()
    {
        return $this->belongsToMany(
            VideoTag::class,
            'video_tag_map',
            'video_id',
            'video_tag_id'
        );
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'video_id', 'video_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'video_id', 'video_id');
    }

    public function videoAccesses()
    {
        return $this->hasMany(VideoAccess::class, 'video_id', 'video_id');
    }
}
