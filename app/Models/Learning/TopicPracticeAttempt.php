<?php

namespace App\Models\Learning;

use Illuminate\Database\Eloquent\Model;

class TopicPracticeAttempt extends Model
{
    protected $fillable = [
        'student_id',
        'topic_id',
        'score',
        'total_questions',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime'
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
