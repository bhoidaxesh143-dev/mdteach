<?php

namespace App\Http\Controllers\Student;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Learning\Topic;
use App\Http\Controllers\Controller;
use App\Models\StudentSubjectAllocation;
use App\Models\Learning\TopicPracticeAttempt;

class TopicPracticeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Practice Page
    |--------------------------------------------------------------------------
    */
    public function show(int $topicId)
    {
        $studentId = auth()->id();

        $topic = Topic::with([
            'chapter.subject',
            'questions.options'
        ])->findOrFail($topicId);

        $this->authorizeAllocatedSubject(
            $studentId,
            $topic->chapter->subject_id
        );

        return Inertia::render(
            'Student/Learning/Practice',
            [
                'topic' => $topic
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Submit Practice
    |--------------------------------------------------------------------------
    */
    public function submit(Request $request, int $topicId)
    {
        $studentId = auth()->id();

        $topic = Topic::with([
            'chapter.subject',
            'questions.options'
        ])->findOrFail($topicId);

        $this->authorizeAllocatedSubject(
            $studentId,
            $topic->chapter->subject_id
        );

        $answers = $request->answers ?? [];

        $score = 0;

        foreach ($topic->questions as $question) {
            $correct = $question->options
                ->firstWhere('is_correct', true);

            if (
                isset($answers[$question->id]) &&
                $answers[$question->id] == $correct?->id
            ) {
                $score++;
            }
        }

        $attempt = TopicPracticeAttempt::create([
            'student_id' => $studentId,
            'topic_id' => $topicId,
            'score' => $score,
            'total_questions' => $topic->questions->count(),
            'completed_at' => now(),
        ]);

        return redirect()->route(
            'student.topic-practice.result',
            $attempt->id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Result Page
    |--------------------------------------------------------------------------
    */
    public function result(int $attemptId)
    {
        $attempt = TopicPracticeAttempt::with(
            'topic.chapter.subject'
        )->findOrFail($attemptId);

        $this->authorizeAllocatedSubject(
            auth()->id(),
            $attempt->topic->chapter->subject_id
        );

        return Inertia::render(
            'Student/Learning/PracticeResult',
            [
                'attempt' => $attempt
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Allocation Guard
    |--------------------------------------------------------------------------
    */
    private function authorizeAllocatedSubject(
        int $studentId,
        int $subjectId
    ): void {
        $isAllocated = StudentSubjectAllocation::where([
            'student_id' => $studentId,
            'subject_id' => $subjectId
        ])->exists();

        abort_unless(
            $isAllocated,
            403,
            'Unauthorized Subject Access.'
        );
    }
}
