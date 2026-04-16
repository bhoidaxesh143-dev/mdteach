<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'exam_id',
        'question',
        'type',
        'marks',
        'sort_order',
        'explanation',
    ];


    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function subject()
{
    return $this->belongsTo(Subject::class);
}

public function topics()
{
    return $this->belongsToMany(
        \App\Models\Learning\Topic::class,
        'topic_question_map',
        'question_id',
        'topic_id'
    );
}
}
