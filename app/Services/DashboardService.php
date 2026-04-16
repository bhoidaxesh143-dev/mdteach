<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\User;
use App\Models\ExamAttempt;

class DashboardService extends BaseService
{
    public function stats(): array
    {
        return [
            'total_exams' => Exam::count(),

            'total_students' => User::whereHas(
                'role',
                fn ($q) => $q->where('name', 'student')
            )->count(),

            'total_attempts' => ExamAttempt::count(),

            'average_score' => round(
                ExamAttempt::whereNotNull('score')->avg('score') ?? 0,
                2
            ),
        ];
    }

    public function topScorers(int $limit = 5)
    {
        return ExamAttempt::with(['exam', 'exam.creator'])
            ->whereNotNull('score')
            ->orderByDesc('score')
            ->limit($limit)
            ->get();
    }

    public function recentAttempts(int $limit = 10)
    {
        return ExamAttempt::with('exam')
            ->latest()
            ->limit($limit)
            ->get();
    }
}
