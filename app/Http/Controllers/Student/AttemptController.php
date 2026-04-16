<?php

namespace App\Http\Controllers\Student;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\ExamAttempt;
use App\Services\AttemptService;
use App\Http\Controllers\Controller;

class AttemptController extends Controller
{
    public function __construct(
        protected AttemptService $attemptService
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Start Exam Attempt
    |--------------------------------------------------------------------------
    */
    public function start(int $examId)
    {
        $attempt = $this->attemptService->startExam($examId);

        return redirect("/student/attempt/{$attempt->id}");
    }

    /*
    |--------------------------------------------------------------------------
    | Show Exam Interface
    |--------------------------------------------------------------------------
    */
    public function show(int $attemptId)
    {
        $attempt = ExamAttempt::with([
                'exam',
                'answers',
                'exam.questions.options'
            ])
            ->findOrFail($attemptId);

        /*
        |--------------------------------------------------------------------------
        | Shuffle / Sort Questions
        |--------------------------------------------------------------------------
        */
        if ($attempt->exam?->shuffle_questions) {
            $attempt->exam->questions =
                $attempt->exam->questions->shuffle()->values();
        } else {
            $attempt->exam->questions =
                $attempt->exam->questions
                    ->sortBy('sort_order')
                    ->values();
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Remaining Time
        |--------------------------------------------------------------------------
        */
        $remainingSeconds = max(
            0,
            $attempt->started_at
                ->addMinutes($attempt->exam->duration_minutes)
                ->diffInSeconds(now(), false) * -1
        );

        return Inertia::render('Student/Exams/ExamInterface', [
            'attempt' => [
                ...$attempt->toArray(),
                'remaining_seconds' => $remainingSeconds,
            ]
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Save Single Answer
    |--------------------------------------------------------------------------
    */
    public function saveAnswer(Request $request, int $attemptId)
    {
        $validated = $request->validate([
            'question_id' => ['required', 'exists:questions,id'],
            'selected_option_id' => ['required', 'exists:question_options,id'],
        ]);

        $this->attemptService->saveAnswer(
            $attemptId,
            $validated['question_id'],
            $validated['selected_option_id']
        );

        return back();
    }

    /*
    |--------------------------------------------------------------------------
    | Submit Exam
    |--------------------------------------------------------------------------
    */
    public function submit(int $attemptId)
    {
        $attempt = $this->attemptService->submit($attemptId);

   return redirect()->route(
    'student.results.show',
    $attempt->id
);
    }
}
