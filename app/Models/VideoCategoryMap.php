<?php
namespace App\Models;

use App\Models\BaseModel;


class VideoCategoryMap extends BaseModel
{
    protected $primaryKey = 'video_category_map_id';
    protected $table = 'video_category_map';
    public $timestamps = true;

    protected $fillable = [
        'video_id',
        'video_category_id',
    ];

    public function video()
    {
        return $this->belongsTo(Video::class, 'video_id', 'video_id');
    }

    public function category()
    {
        return $this->belongsTo(VideoCategory::class, 'video_category_id', 'video_category_id');
    }
}
