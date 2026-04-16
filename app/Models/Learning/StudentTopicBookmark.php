<?php

namespace App\Models\Learning;

use Illuminate\Database\Eloquent\Model;

class StudentTopicBookmark extends Model
{
    protected $fillable = [
        'student_id',
        'topic_id'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function topic()
    {
        return $this->belongsTo(
            Topic::class,
            'topic_id'
        );
    }

    public function student()
    {
        return $this->belongsTo(
            \App\Models\User::class,
            'student_id'
        );
    }
}
