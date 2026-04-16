<?php

namespace App\Imports;

use App\Models\Question;
use App\Models\Subject;
use App\Models\QuestionOption;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class QuestionsImport implements ToCollection
{
    public function __construct(
        protected int $examId
    ) {}

    public function collection(Collection $rows)
    {
        $rows->shift();

        foreach ($rows as $row) {

            $subject = Subject::firstOrCreate([
                'name' => trim($row[0])
            ]);

            $question = Question::create([
                'exam_id' => $this->examId,
                'subject_id' => $subject->id,
                'question' => $row[1],
                'type' => $row[2],
                'marks' => $row[3],
                'sort_order' => $row[9] ?? 0,
            ]);

            $correctAnswers = explode('|', $row[8]);

            $options = [
                'A' => $row[4],
                'B' => $row[5],
                'C' => $row[6],
                'D' => $row[7],
            ];

            foreach ($options as $key => $text) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $text,
                    'is_correct' => in_array($key, $correctAnswers),
                ]);
            }
        }
    }
}
