<?php

namespace App\Models;

use App\Models\BaseModel;

class VideoTag extends BaseModel
{
    protected $primaryKey = 'video_tag_id';
    protected $table = 'video_tags';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'slug',
        'description'
    ];

    public function videos()
    {
        return $this->belongsToMany(
            Video::class,
            'video_tag_map',
            'video_tag_id',
            'video_id'
        );
    }
}
