<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Learning\Topic;
use App\Models\Learning\Chapter;
use App\Http\Controllers\Controller;

class TopicController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Topics/Index', [
            'topics' => Topic::with([
                'chapter',
                'parent'
            ])->latest()->paginate(20)
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Topics/Create', [
            'chapters' => Chapter::all(),
            'parents' => Topic::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'chapter_id' => 'required|exists:chapters,id',
            'parent_id' => 'nullable|exists:topics,id',
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        Topic::create([
            ...$validated,
            'slug' => Str::slug($validated['title']),
        ]);

        return redirect()
            ->route('admin.topics.index')
            ->with('success', 'Topic created.');
    }

    public function edit(Topic $topic)
{
    return Inertia::render('Admin/Topics/Edit', [
        'topic' => $topic,
        'chapters' => Chapter::all(),
        'parents' => Topic::where('id', '!=', $topic->id)->get(),
    ]);
}

public function destroy(Topic $topic)
{
    $topic->delete();

    return redirect()
        ->route('admin.topics.index')
        ->with('success', 'Topic deleted successfully.');
}

public function update(Request $request, Topic $topic)
{
    $validated = $request->validate([
        'chapter_id' => 'required|exists:chapters,id',
        'parent_id' => 'nullable|exists:topics,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'sort_order' => 'nullable|integer',
    ]);

    $topic->update([
        ...$validated,
        'slug' => Str::slug($validated['title']),
    ]);

    return redirect()
        ->route('admin.topics.index')
        ->with('success', 'Topic updated successfully.');
}
}
