<?php
namespace App\Models;

use App\Models\BaseModel;

class VideoTagMap extends BaseModel
{
    protected $primaryKey = 'video_tag_map_id';
    protected $table = 'video_tag_map';
    public $timestamps = true;

    protected $fillable = [
        'video_id',
        'video_tag_id',
    ];

    public function video()
    {
        return $this->belongsTo(Video::class, 'video_id', 'video_id');
    }

    public function tag()
    {
        return $this->belongsTo(VideoTag::class, 'video_tag_id', 'video_tag_id');
    }
}
