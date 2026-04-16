<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\QuestionService;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Subject;

class QuestionController extends Controller
{
    public function __construct(
        protected QuestionService $questionService
    ) {}

    /**
     * Display all questions for exam
     */
    public function index(Request $request, int $examId)
    {
        $exam = Exam::findOrFail($examId);

        $questions = Question::with(['options', 'subject'])
            ->where('exam_id', $examId)
            ->when(
                $request->search,
                fn ($query, $search) =>
                    $query->where('question', 'like', "%{$search}%")
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Questions/Index', [
            'exam' => $exam,
            'questions' => $questions,
            'filters' => [
                'search' => $request->search
            ]
        ]);
    }

    /**
     * Show create form
     */
    public function create(int $examId)
    {
        return Inertia::render('Admin/Questions/Create', [
            'examId' => $examId,
            'subjects' => Subject::where('is_active', true)
                ->orderBy('name')
                ->get(),
        ]);
    }

    /**
     * Store question
     */
    public function store(Request $request, int $examId)
    {
        $validated = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'question' => ['required', 'string'],
            'type' => ['required', 'in:single_choice,multiple_choice'],
            'marks' => ['required', 'numeric', 'min:0'],
            'explanation' => ['nullable', 'string'],

            'options' => ['required', 'array', 'min:2'],
            'options.*.text' => ['required', 'string'],
            'options.*.is_correct' => ['required', 'boolean'],
        ]);

        $validated['exam_id'] = $examId;

        $correctCount = collect($validated['options'])
            ->where('is_correct', true)
            ->count();

        if ($correctCount === 0) {
            return back()->withErrors([
                'options' => 'At least one correct option is required.'
            ]);
        }

        $this->questionService->create($validated);

        return redirect()
            ->to("/admin/exams/{$examId}/questions")
            ->with('success', 'Question added successfully.');
    }

    /**
     * Show edit form
     */
    public function edit(int $id)
    {
        $question = Question::with('options')
            ->findOrFail($id);

        return Inertia::render('Admin/Questions/Edit', [
            'question' => $question
        ]);
    }

    /**
     * Update question
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'question' => ['required', 'string'],
            'marks' => ['required', 'numeric', 'min:0'],
            'explanation' => ['nullable', 'string'],

            'options' => ['required', 'array', 'min:2'],
            'options.*.id' => ['required', 'exists:question_options,id'],
            'options.*.option_text' => ['required', 'string'],
            'options.*.is_correct' => ['boolean'],
        ]);

        $question = Question::with('options')->findOrFail($id);

        $correctCount = collect($validated['options'])
            ->where('is_correct', true)
            ->count();

        if ($correctCount === 0) {
            return back()->withErrors([
                'options' => 'At least one correct option is required.'
            ]);
        }

        $question->update([
            'question' => $validated['question'],
            'marks' => $validated['marks'],
            'explanation' => $validated['explanation'] ?? null,
        ]);

        foreach ($validated['options'] as $optionData) {
            $question->options()
                ->where('id', $optionData['id'])
                ->update([
                    'option_text' => $optionData['option_text'],
                    'is_correct' => $optionData['is_correct'] ?? false,
                ]);
        }

        return redirect()
            ->to("/admin/exams/{$question->exam_id}/questions")
            ->with('success', 'Question updated successfully.');
    }

    /**
     * Delete question
     */
    public function destroy(int $id)
    {
        $question = Question::findOrFail($id);

        $examId = $question->exam_id;

        $question->delete();

        return redirect()
            ->to("/admin/exams/{$examId}/questions")
            ->with('success', 'Question deleted successfully.');
    }
}
