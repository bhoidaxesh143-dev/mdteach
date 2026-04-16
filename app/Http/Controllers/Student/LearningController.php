<?php

namespace App\Http\Controllers\Student;

use App\Helpers\StudentAllocationHelper;
use Inertia\Inertia;
use App\Models\Subject;
use App\Models\Learning\Topic;
use App\Http\Controllers\Controller;
use App\Models\Learning\StudentTopicNote;
use App\Models\Learning\StudentTopicProgress;
use App\Models\StudentSubjectAllocation;

class LearningController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Learning Hub Index
    |--------------------------------------------------------------------------
    */
   public function index()
    {
        $studentId = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Allocated Subjects Only
        |--------------------------------------------------------------------------
        */
        $allocatedSubjectIds = StudentSubjectAllocation::where(
            'student_id',
            $studentId
        )->pluck('subject_id');

        $subjects = Subject::with([
            'chapters.topics.children'
        ])
        ->whereIn('id', $allocatedSubjectIds)
        ->get();

        /*
        |--------------------------------------------------------------------------
        | Analytics
        |--------------------------------------------------------------------------
        */
        $totalTopics = Topic::count();

        $completedTopics = StudentTopicProgress::where([
            'student_id' => $studentId,
            'is_completed' => true
        ])->count();

        $progressPercent = $totalTopics
            ? round(($completedTopics / $totalTopics) * 100)
            : 0;

        return Inertia::render('Student/Learning/Index', [
            'subjects' => $subjects,

            'progress' => StudentTopicProgress::where(
                'student_id',
                $studentId
            )->pluck('is_completed', 'topic_id'),

            'analytics' => [
                'total_topics' => $totalTopics,
                'completed_topics' => $completedTopics,
                'progress_percent' => $progressPercent,
            ]
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Show Topic Reader
    |--------------------------------------------------------------------------
    */
    public function showTopic(int $topicId)
    {
        $studentId = auth()->id();


        $topic = Topic::with([
            'content',
            'chapter.subject',
            'children',
            'questions'
        ])->findOrFail($topicId);


                $allocatedSubjectIds = StudentAllocationHelper::allocatedSubjectIds(
    auth()->id()
);

abort_unless(
    in_array($topic->chapter->subject_id, $allocatedSubjectIds->toArray()),
    403
);
        /*
        |--------------------------------------------------------------------------
        | Load Student Note
        |--------------------------------------------------------------------------
        */
        $topic->my_note = StudentTopicNote::where([
            'student_id' => $studentId,
            'topic_id'   => $topicId,
        ])->first();

        /*
        |--------------------------------------------------------------------------
        | Completion Status
        |--------------------------------------------------------------------------
        */
        $topic->is_completed = StudentTopicProgress::where([
            'student_id' => $studentId,
            'topic_id'   => $topicId,
        ])
        ->where('is_completed', true)
        ->exists();

        return Inertia::render('Student/Learning/Reader', [
            'topic' => $topic
        ]);
    }
}
