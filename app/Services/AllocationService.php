<?php

namespace App\Services;

use App\Models\StudentExamAllocation;

class AllocationService extends BaseService
{
    public function allocate(int $studentId, int $examId)
    {
        return StudentExamAllocation::firstOrCreate([
            'student_id' => $studentId,
            'exam_id' => $examId,
        ]);
    }
// public function getStudentExams(int $studentId)
// {
//     return StudentExamAllocation::with('exam')
//         ->where('student_id', $studentId)
//         ->get()
//         ->map(function ($allocation) {
//             return [
//                 'id' => $allocation->exam->id,
//                 'title' => $allocation->exam->title,
//                 'duration_minutes' => $allocation->exam->duration_minutes,
//                 'negative_marking' => $allocation->exam->negative_marking,
//                 'starts_at' => $allocation->exam->starts_at,
//                 'ends_at' => $allocation->exam->ends_at,
//                 'is_completed' => $allocation->is_completed,
//             ];
//         });
// }

public function getStudentExams(int $studentId)
{
    return StudentExamAllocation::with('exam')
        ->where('student_id', $studentId)
        ->get()
        ->map(function ($allocation) use ($studentId) {

            $attemptsUsed = \App\Models\ExamAttempt::where([
                'student_id' => $studentId,
                'exam_id' => $allocation->exam_id,
            ])->count();

            return [
                ...$allocation->exam->toArray(),

                'max_attempts' => $allocation->exam->max_attempts,

                'attempts_used' => $attemptsUsed,

                'remaining_attempts' => max(
                    0,
                    $allocation->exam->max_attempts - $attemptsUsed
                ),
            ];
        });
}
}
