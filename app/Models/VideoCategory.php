<?php

namespace App\Models;

use App\Models\BaseModel;

class VideoCategory extends BaseModel
{
    protected $primaryKey = 'video_category_id';
    protected $table = 'video_categories';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function videos()
    {
        return $this->belongsToMany(
            Video::class,
            'video_category_map',
            'video_category_id',
            'video_id'
        );
    }
}
