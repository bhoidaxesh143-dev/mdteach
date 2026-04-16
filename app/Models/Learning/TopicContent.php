<?php

namespace App\Models\Learning;

use Illuminate\Database\Eloquent\Model;

class TopicContent extends Model
{
    protected $fillable = [
        'topic_id',
        'content_html',
        'shortcut_tips',
        'video_url',
        'diagram_url',
        'estimated_read_minutes',
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
