<?php

namespace App\Repositories;

use App\Models\Question;
use App\Repositories\Interfaces\QuestionRepositoryInterface;

class QuestionRepository extends BaseRepository implements QuestionRepositoryInterface
{
    public function __construct(Question $question)
    {
        $this->model = $question;
    }

    public function getByExam(int $examId)
    {
        return $this->model
            ->with('options')
            ->where('exam_id', $examId)
            ->orderBy('sort_order')
            ->get();
    }
}
