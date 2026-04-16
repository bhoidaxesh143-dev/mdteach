<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    protected $fillable = [
        'student_id',
        'exam_id',
        'started_at',
        'submitted_at',
        'score',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function answers()
    {
        return $this->hasMany(
            AttemptAnswer::class,
            'attempt_id'
        );
    }

    public function student()
    {
        return $this->belongsTo(
            User::class,
            'student_id'
        );
    }
}
