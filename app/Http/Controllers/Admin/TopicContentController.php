<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Learning\Topic;
use App\Models\Learning\TopicContent;
use App\Http\Controllers\Controller;

class TopicContentController extends Controller
{
    /**
     * Display all topic contents
     */
    public function index(Request $request)
    {
        $topics = Topic::with('content')
            ->when(
                $request->search,
                fn ($query, $search) =>
                    $query->where('title', 'like', "%{$search}%")
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/TopicContents/Index', [
            'topics' => $topics,

            'filters' => [
                'search' => $request->search
            ]
        ]);
    }

    /**
     * Show edit form
     */
    public function edit(int $topicId)
    {
        $topic = Topic::with('content')
            ->findOrFail($topicId);

        return Inertia::render('Admin/TopicContents/Edit', [
            'topic' => $topic
        ]);
    }

    /**
     * Update / Create topic content
     */
    public function update(Request $request, int $topicId)
    {
        $validated = $request->validate([
            'content_html' => [
                'required',
                'string'
            ],

            'shortcut_tips' => [
                'nullable',
                'string'
            ],

            'video_url' => [
                'nullable',
                'url'
            ],

            'diagram_url' => [
                'nullable',
                'url'
            ],

            'estimated_read_minutes' => [
                'nullable',
                'integer',
                'min:1',
                'max:999'
            ],
        ]);

        Topic::findOrFail($topicId);

        TopicContent::updateOrCreate(
            [
                'topic_id' => $topicId
            ],
            $validated
        );

        return redirect()
            ->route('admin.topic-content.index')
            ->with(
                'success',
                'Topic content saved successfully.'
            );
    }
}
