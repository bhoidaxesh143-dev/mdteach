<?php

namespace App\Repositories;

use App\Models\Exam;
use App\Repositories\Interfaces\ExamRepositoryInterface;

class ExamRepository extends BaseRepository implements ExamRepositoryInterface
{
    public function __construct(Exam $exam)
    {
        $this->model = $exam;
    }
    public function paginate(int $perPage = 15)
{
    return $this->model
        ->when(request('search'), function ($query) {
            $query->where('title', 'like', '%' . request('search') . '%');
        })
        ->latest()
        ->paginate($perPage)
        ->withQueryString();
}
}
