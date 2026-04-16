<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Learning\StudentTopicBookmark;

class BookmarkController extends Controller
{
    public function toggle(int $topicId)
    {
        $bookmark = StudentTopicBookmark::where([
            'student_id' => auth()->id(),
            'topic_id' => $topicId
        ])->first();

        if ($bookmark) {
            $bookmark->delete();
        } else {
            StudentTopicBookmark::create([
                'student_id' => auth()->id(),
                'topic_id' => $topicId
            ]);
        }

        return back();
    }
}
