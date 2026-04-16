<?php

namespace App\Http\Controllers\Student;

use Inertia\Inertia;
use App\Models\ExamAttempt;
use App\Http\Controllers\Controller;
use App\Models\StudentExamAllocation;
use App\Models\Learning\StudentGamification;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $studentId = auth()->id();

        return Inertia::render('Student/Dashboard', [
            'stats' => [
                'allocated' => StudentExamAllocation::where(
                    'student_id',
                    $studentId
                )->count(),

                'completed' => StudentExamAllocation::where(
                    'student_id',
                    $studentId
                )
                ->where('is_completed', true)
                ->count(),

                'pending' => StudentExamAllocation::where(
                    'student_id',
                    $studentId
                )
                ->where('is_completed', false)
                ->count(),

                'average_score' => round(
                    ExamAttempt::where(
                        'student_id',
                        $studentId
                    )->avg('score') ?? 0,
                    2
                ),
            ],

            'recentAttempts' => ExamAttempt::with('exam')
                ->where('student_id', $studentId)
                ->latest()
                ->take(5)
                ->get(),

            'gamification' => StudentGamification::firstWhere(
                'student_id',
                $studentId
            ),
        ]);
    }
}
