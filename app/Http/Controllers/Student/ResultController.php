<?php

namespace App\Http\Controllers\Student;

use Inertia\Inertia;
use App\Models\ExamAttempt;
use App\Http\Controllers\Controller;

class ResultController extends Controller
{
    /**
     * Results List Page
     */
    public function index()
    {
        $results = ExamAttempt::with('exam')
            ->where('student_id', auth()->id())
            ->latest()
            ->get();

        return Inertia::render(
            'Student/Results/Index',
            [
                'results' => $results
            ]
        );
    }

    /**
     * Single Result Detail
     */
    public function show(int $attemptId)
    {
        $attempt = ExamAttempt::with([
            'exam.questions.options',
            'answers'
        ])
        ->where('student_id', auth()->id())
        ->findOrFail($attemptId);

        return Inertia::render(
            'Student/Results/Show',
            [
                'attempt' => $attempt
            ]
        );
    }
}
