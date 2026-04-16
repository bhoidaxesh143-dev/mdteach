<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentExamAllocation extends Model
{
    protected $fillable = [
        'student_id',
        'exam_id',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
