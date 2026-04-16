<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\Learning\Topic;
use App\Http\Controllers\Controller;

class TopicQuestionMapController extends Controller
{
    /**
     * Show mapping page
     */
    public function edit(int $topicId)
    {
        $topic = Topic::with('questions')
            ->findOrFail($topicId);

        $questions = Question::select(
                'id',
                'question'
            )
            ->latest()
            ->get();

        return Inertia::render(
            'Admin/Topics/TopicQuestionMap/Edit',
            [
                'topic' => $topic,
                'questions' => $questions
            ]
        );
    }

    /**
     * Save mapping
     */
    public function update(
        Request $request,
        int $topicId
    ) {
        $validated = $request->validate([
            'question_ids' => ['nullable', 'array'],
            'question_ids.*' => ['exists:questions,id']
        ]);

        $topic = Topic::findOrFail($topicId);

        $topic->questions()->sync(
            $validated['question_ids'] ?? []
        );

        return back()->with(
            'success',
            'Questions linked successfully.'
        );
    }
}
