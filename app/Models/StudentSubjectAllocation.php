<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentSubjectAllocation extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'allocated_by'
    ];
}
