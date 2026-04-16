<?php

namespace App\Services;

use App\Models\ExamAttempt;
use App\Models\AttemptAnswer;
use App\Models\StudentExamAllocation;

class AttemptService extends BaseService
{
public function startExam(int $examId): ExamAttempt
{
    $exam = \App\Models\Exam::findOrFail($examId);

    /*
    |--------------------------------------------------------------------------
    | Basic Validations
    |--------------------------------------------------------------------------
    */
    if (!$exam->is_active) {
        abort(403, 'Exam inactive.');
    }

    if ($exam->starts_at && now()->lt($exam->starts_at)) {
        abort(403, 'Exam has not started yet.');
    }

    if ($exam->ends_at && now()->gt($exam->ends_at)) {
        abort(403, 'Exam has ended.');
    }

    /*
    |--------------------------------------------------------------------------
    | Resume Existing In-Progress Attempt
    |--------------------------------------------------------------------------
    */
    $inProgressAttempt = ExamAttempt::where([
        'student_id' => auth()->id(),
        'exam_id' => $examId,
        'status' => 'in_progress',
    ])->first();

    if ($inProgressAttempt) {
        return $inProgressAttempt;
    }

    /*
    |--------------------------------------------------------------------------
    | Attempt Limit Validation
    |--------------------------------------------------------------------------
    */
    $attemptCount = ExamAttempt::where([
        'student_id' => auth()->id(),
        'exam_id' => $examId,
    ])->whereIn('status', ['submitted', 'expired'])
      ->count();

    if ($attemptCount >= $exam->max_attempts) {
        abort(403, 'Maximum attempt limit reached.');
    }

    /*
    |--------------------------------------------------------------------------
    | Create New Attempt
    |--------------------------------------------------------------------------
    */
    return ExamAttempt::create([
        'student_id' => auth()->id(),
        'exam_id' => $examId,
        'started_at' => now(),
        'status' => 'in_progress',
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);
}

 public function saveAnswer(
    int $attemptId,
    int $questionId,
    int $selectedOptionId
) {
    return AttemptAnswer::updateOrCreate(
        [
            'attempt_id' => $attemptId,
            'question_id' => $questionId,
        ],
        [
            'selected_option_id' => $selectedOptionId,
        ]
    );
}

   public function submit(int $attemptId): ExamAttempt
{
    $attempt = ExamAttempt::with([
        'exam.questions.options',
        'answers'
    ])->findOrFail($attemptId);

    if ($attempt->status !== 'in_progress') {
        return $attempt;
    }

    $expiry = $attempt->started_at
        ->copy()
        ->addMinutes($attempt->exam->duration_minutes);

    $isExpired = now()->greaterThan($expiry);

    $score = 0;

    foreach ($attempt->answers as $answer) {
        $question = $attempt->exam->questions
            ->firstWhere('id', $answer->question_id);

        if (!$question) {
            continue;
        }

        $correctOption = $question->options
            ->firstWhere('is_correct', true);

        if (!$correctOption) {
            continue;
        }

        $isCorrect = (int)$answer->selected_option_id === (int)$correctOption->id;

        $awardedMarks = $isCorrect
            ? $question->marks
            : -$attempt->exam->negative_marking;

        $answer->update([
            'is_correct' => $isCorrect,
            'awarded_marks' => $awardedMarks,
        ]);

        $score += $awardedMarks;
    }



    $attempt->update([
        'submitted_at' => now(),
        'status' => $isExpired ? 'expired' : 'submitted',
        'score' => max($score, 0),
    ]);

    StudentExamAllocation::where([
        'student_id' => $attempt->student_id,
        'exam_id' => $attempt->exam_id,
    ])->update([
        'is_completed' => true
    ]);

    return $attempt;
}
}
