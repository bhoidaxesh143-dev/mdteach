<?php

namespace App\Models\Learning;

use Illuminate\Database\Eloquent\Model;
use App\Models\Question;

class Topic extends Model
{
    protected $fillable = [
        'chapter_id',
        'parent_id',
        'title',
        'slug',
        'summary',
        'sort_order',
        'is_active',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function parent()
    {
        return $this->belongsTo(
            self::class,
            'parent_id'
        );
    }

    public function children()
    {
        return $this->hasMany(
            self::class,
            'parent_id'
        )->orderBy('sort_order');
    }

public function content()
{
    return $this->hasOne(TopicContent::class)->withDefault();
}

    public function questions()
    {
        return $this->belongsToMany(
            Question::class,
            'topic_question_map',
            'topic_id',
            'question_id'
        );
    }
}
