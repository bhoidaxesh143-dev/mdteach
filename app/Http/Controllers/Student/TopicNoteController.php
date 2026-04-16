<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Learning\StudentTopicNote;

class TopicNoteController extends Controller
{
    public function save(Request $request, int $topicId)
    {
        $request->validate([
            'note' => 'required|string'
        ]);

        StudentTopicNote::updateOrCreate(
            [
                'student_id' => auth()->id(),
                'topic_id' => $topicId
            ],
            [
                'note' => $request->note
            ]
        );

        return back();
    }


}
