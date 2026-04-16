<?php

namespace App\Http\Controllers\Student;

use Inertia\Inertia;
use App\Models\Learning\Topic;
use App\Http\Controllers\Controller;
use App\Helpers\StudentAllocationHelper;
use App\Models\Learning\StudentTopicNote;
use App\Models\Learning\StudentTopicBookmark;
use App\Models\Learning\TopicPracticeAttempt;
use App\Models\Learning\StudentTopicProgress;

class RevisionDashboardController extends Controller
{
    public function index()
    {
        $studentId = auth()->id();

        $allocatedSubjectIds = StudentAllocationHelper::allocatedSubjectIds(
            $studentId
        );

        /*
        |--------------------------------------------------------------------------
        | Continue Learning
        |--------------------------------------------------------------------------
        */
        $lastRead = StudentTopicProgress::with('topic')
            ->where('student_id', $studentId)
            ->whereHas('topic.chapter.subject', function ($q) use ($allocatedSubjectIds) {
                $q->whereIn('id', $allocatedSubjectIds);
            })
            ->latest('last_read_at')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Weak Topics
        |--------------------------------------------------------------------------
        */
        $weakTopicIds = TopicPracticeAttempt::where(
            'student_id',
            $studentId
        )
        ->get()
        ->filter(fn ($attempt) =>
            $attempt->total_questions > 0 &&
            (($attempt->score / $attempt->total_questions) * 100) < 50
        )
        ->pluck('topic_id');

        $weakTopics = Topic::whereIn('id', $weakTopicIds)
            ->whereHas('chapter.subject', function ($q) use ($allocatedSubjectIds) {
                $q->whereIn('id', $allocatedSubjectIds);
            })
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Bookmarks
        |--------------------------------------------------------------------------
        */
        $bookmarks = StudentTopicBookmark::with('topic')
            ->where('student_id', $studentId)
            ->whereHas('topic.chapter.subject', function ($q) use ($allocatedSubjectIds) {
                $q->whereIn('id', $allocatedSubjectIds);
            })
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Notes
        |--------------------------------------------------------------------------
        */
        $notes = StudentTopicNote::with('topic')
            ->where('student_id', $studentId)
            ->whereHas('topic.chapter.subject', function ($q) use ($allocatedSubjectIds) {
                $q->whereIn('id', $allocatedSubjectIds);
            })
            ->get();

        return Inertia::render(
            'Student/Revision/Dashboard',
            [
                'continueLearning' => $lastRead,
                'weakTopics' => $weakTopics,
                'bookmarks' => $bookmarks,
                'notes' => $notes,
            ]
        );
    }
}
