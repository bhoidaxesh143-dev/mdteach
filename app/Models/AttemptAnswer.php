<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttemptAnswer extends Model
{
    protected $fillable = [
        'attempt_id',
        'question_id',
        'selected_options',
        'selected_option_id',
        'is_correct',
        'awarded_marks'
    ];

    protected $casts = [
        'selected_options' => 'array',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    public function selectedOption()
{
    return $this->belongsTo(
       QuestionOption::class,
        'selected_option_id'
    );
}
}
