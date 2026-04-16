<?php

namespace App\Exports;

use App\Models\ExamAttempt;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExamResultsExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected ?int $examId = null
    ) {}

    public function collection()
    {
        return ExamAttempt::with(['student', 'exam'])
            ->when($this->examId, function ($query) {
                $query->where('exam_id', $this->examId);
            })
            ->get()
            ->map(function ($attempt) {
                return [
                    'Student Name' => $attempt->student?->name,
                    'Student Email' => $attempt->student?->email,
                    'Exam Title' => $attempt->exam?->title,
                    'Score' => $attempt->score,
                    'Status' => $attempt->status,
                    'Started At' => $attempt->started_at,
                    'Submitted At' => $attempt->submitted_at,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Student Email',
            'Exam Title',
            'Score',
            'Status',
            'Started At',
            'Submitted At',
        ];
    }
}
