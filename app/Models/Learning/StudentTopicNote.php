<?php

namespace App\Models\Learning;

use Illuminate\Database\Eloquent\Model;

class StudentTopicNote extends Model
{
    protected $fillable = [
        'student_id',
        'topic_id',
        'note'
    ];

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
