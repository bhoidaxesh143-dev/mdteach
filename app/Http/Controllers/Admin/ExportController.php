<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\ExamAttempt;
use App\Exports\ExamResultsExport;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Reports Page
     */
    public function index()
    {
        $attempts = ExamAttempt::with(['student', 'exam'])
            ->latest()
            ->paginate(20);

        return Inertia::render('Admin/Reports/Index', [
            'attempts' => $attempts
        ]);
    }

    /**
     * Export Excel
     */
    public function examResults(?int $examId = null)
    {
        return Excel::download(
            new ExamResultsExport($examId),
            'exam-results.xlsx'
        );
    }

    /**
     * Export PDF
     */
    public function examResultsPdf(?int $examId = null)
    {
        $query = ExamAttempt::with(['student', 'exam']);

        if ($examId) {
            $query->where('exam_id', $examId);
        }

        $results = $query->latest()->get();

       $pdf = Pdf::loadView(
    'admin.reports.exam-results-pdf',
    [
        'results' => $results
    ]
)->setPaper('a4', 'landscape');

        return $pdf->download('exam-results.pdf');
    }
}
