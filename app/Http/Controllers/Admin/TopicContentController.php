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
    $topic = Topic::with('content')->findOrFail($topicId);

    return Inertia::render('Admin/TopicContents/Edit', [
        'topic' => $topic ?? new Topic()
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

                 // 🔥 Find used images
    preg_match_all('/<img[^>]+src="([^">]+)"/', $request->content_html, $matches);
    $usedImages = $matches[1] ?? [];

    // 🔥 Delete unused
   $allImages = glob(base_path('../public_html/uploads/*'));

    foreach ($allImages as $file) {
        $url = asset('uploads/' . basename($file));

        if (!in_array($url, $usedImages)) {
            @unlink($file);
        }
    }

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
