<?php

namespace App\Http\Controllers\Student;

use App\Models\Learning\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Learning\StudentTopicProgress;

class LearningProgressController extends Controller
{
    public function markComplete(int $topicId)
    {
        $studentId = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Mark Current Topic Complete
        |--------------------------------------------------------------------------
        */
        StudentTopicProgress::updateOrCreate(
            [
                'student_id' => $studentId,
                'topic_id'   => $topicId,
            ],
            [
                'is_completed' => true,
                'completed_at' => now(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Auto Complete Parent Topic If All Children Done
        |--------------------------------------------------------------------------
        */
        $topic = Topic::with('parent')->findOrFail($topicId);

        if ($topic->parent_id) {

            $parent = Topic::with('children')->find($topic->parent_id);

            $childIds = $parent->children->pluck('id');

            $completedCount = StudentTopicProgress::where(
                'student_id',
                $studentId
            )
            ->whereIn('topic_id', $childIds)
            ->where('is_completed', true)
            ->count();

            if ($completedCount === $childIds->count()) {

                StudentTopicProgress::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'topic_id'   => $parent->id,
                    ],
                    [
                        'is_completed' => true,
                        'completed_at' => now(),
                    ]
                );
            }
        }

        return back()->with(
            'success',
            'Progress updated successfully.'
        );
    }
}
