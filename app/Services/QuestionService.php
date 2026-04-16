<?php

namespace App\Services;

use App\Models\QuestionOption;
use App\Repositories\Interfaces\QuestionRepositoryInterface;

class QuestionService extends BaseService
{
    public function __construct(
        protected QuestionRepositoryInterface $questionRepository
    ) {}

    /**
     * Get questions by exam
     */
    public function getByExam(int $examId)
    {
        return $this->questionRepository->getByExam($examId);
    }

    /**
     * Create question with options
     */
    public function create(array $data)
    {
        $question = $this->questionRepository->create([
            'exam_id' => $data['exam_id'],
            'subject_id' => $data['subject_id'],
            'question' => $data['question'],
            'type' => $data['type'],
            'marks' => $data['marks'],
            'explanation' => $data['explanation'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        foreach ($data['options'] as $option) {
            QuestionOption::create([
                'question_id' => $question->id,
                'option_text' => $option['text'],
                'is_correct' => $option['is_correct'],
            ]);
        }

        return $question;
    }
}
