<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Services\SubjectService;
use App\Http\Controllers\Controller;

class SubjectController extends Controller
{
    public function __construct(
        protected SubjectService $subjectService
    ) {}

    /**
     * Display subject listing with search/filter
     */
    public function index(Request $request)
    {
        $subjects = Subject::query()
            ->when(
                $request->search,
                fn ($query, $search) =>
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Subjects/Index', [
            'subjects' => $subjects,
            'filters' => [
                'search' => $request->search,
            ],
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return Inertia::render('Admin/Subjects/Create');
    }

    /**
     * Store subject
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $this->subjectService->create($validated);

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    /**
     * Show edit form
     */
    public function edit(Subject $subject)
    {
        return Inertia::render('Admin/Subjects/Edit', [
            'subject' => $subject,
        ]);
    }

    /**
     * Update subject
     */
    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name,' . $subject->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $this->subjectService->update($subject->id, $validated);

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    /**
     * Delete subject
     */
    public function destroy(Subject $subject)
    {
        $this->subjectService->delete($subject->id);

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }
}
