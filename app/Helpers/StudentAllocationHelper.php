<?php

namespace App\Helpers;

use App\Models\StudentSubjectAllocation;

class StudentAllocationHelper
{
    public static function allocatedSubjectIds(int $studentId)
    {
        return StudentSubjectAllocation::where(
            'student_id',
            $studentId
        )->pluck('subject_id');
    }
}
