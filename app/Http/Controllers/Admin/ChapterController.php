<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Learning\Chapter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class ChapterController extends Controller
{
 public function index(Request $request)
{
    $chapters = Chapter::with('subject')
        ->when(
            $request->search,
            fn ($query, $search) =>
                $query->where('title', 'like', "%{$search}%")
        )
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return Inertia::render('Admin/Chapters/Index', [
        'chapters' => $chapters,
        'filters' => [
            'search' => $request->search
        ]
    ]);
}

    public function create()
    {
        return Inertia::render('Admin/Chapters/Create', [
            'subjects' => Subject::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        Chapter::create([
            ...$validated,
            'slug' => Str::slug($validated['title']),
        ]);

        return redirect()
            ->route('admin.chapters.index')
            ->with('success', 'Chapter created successfully.');
    }
    public function edit(Chapter $chapter)
{
    return Inertia::render('Admin/Chapters/Edit', [
        'chapter' => $chapter,
        'subjects' => Subject::all(),
    ]);
}

public function update(Request $request, Chapter $chapter)
{
    $validated = $request->validate([
        'subject_id' => 'required|exists:subjects,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'sort_order' => 'nullable|integer',
    ]);

    $chapter->update([
        ...$validated,
        'slug' => Str::slug($validated['title']),
    ]);

    return redirect()
        ->route('admin.chapters.index')
        ->with('success', 'Chapter updated successfully.');
}

public function destroy(Chapter $chapter)
{
    $chapter->delete();

    return redirect()
        ->route('admin.chapters.index')
        ->with('success', 'Chapter deleted successfully.');
}
}
