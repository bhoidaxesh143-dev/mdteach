<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\ExamService;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use Barryvdh\DomPDF\Facade\Pdf;

class ExamController extends Controller
{
    public function __construct(
        protected ExamService $examService
    ) {}

   public function index(Request $request)
{
    $exams = Exam::query()
        ->when(
            $request->search,
            fn ($query, $search) =>
                $query->where('title', 'like', "%{$search}%")
        )
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return Inertia::render('Admin/Exams/Index', [
        'exams' => $exams,
        'filters' => [
            'search' => $request->search
        ]
    ]);
}

    public function create()
    {
        return Inertia::render('Admin/Exams/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'duration_minutes' => 'required|integer|min:1',
            'negative_marking' => 'required|numeric|min:0',
        ]);

        $this->examService->create($validated);

        return redirect('/admin/exams')
            ->with('success', 'Exam created successfully.');
    }

    public function examResultsPdf(?int $examId = null)
{
    $attempts = ExamAttempt::with(['student', 'exam'])
        ->when($examId, function ($query) use ($examId) {
            $query->where('exam_id', $examId);
        })
        ->get();

    $pdf = Pdf::loadView('pdf.exam-results', [
        'attempts' => $attempts
    ]);

    return $pdf->download('exam-results.pdf');
}

public function edit(int $id)
{
    $exam = $this->examService->find($id);

    return Inertia::render('Admin/Exams/Edit', [
        'exam' => $exam
    ]);
}

public function update(Request $request, int $id)
{
    $validated = $request->validate([
        'title' => 'required',
        'duration_minutes' => 'required|integer|min:1',
        'negative_marking' => 'required|numeric|min:0',
    'max_attempts' => 'required|integer|min:1',
    ]);

    $this->examService->update($id, $validated);

    return redirect('/admin/exams')
        ->with('success', 'Exam updated successfully.');
}

public function destroy(int $id)
{
    $this->examService->delete($id);

    return redirect()->back()
        ->with('success', 'Exam deleted successfully.');
}
}
