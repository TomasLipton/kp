<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Topics;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $topicQuestionTypes = [
            'single-text-questions' => 'single_text',
            'year-questions' => 'year',
            'number-questions' => 'number',
            'date-month-questions' => 'date_month',
            'date-month-year-questions' => 'date_month_year',
        ];

        foreach ($topicQuestionTypes as $slug => $questionType) {
            $topic = Topics::where('slug', $slug)->first();

            if (! $topic) {
                continue;
            }

            for ($i = 1; $i <= 5; $i++) {
                $question = Question::create([
                    'question_pl' => $this->getQuestionText($questionType, $i),
                    'question_ru' => $this->getQuestionText($questionType, $i),
                    'question_type' => $questionType,
                    'topics_id' => $topic->id,
                    'is_reviewed' => true,
                ]);

                $this->createAnswers($question, $questionType, $i);
            }
        }
    }

    private function getQuestionText(string $questionType, int $index): string
    {
        return match ($questionType) {
            'single_text' => "Pytanie tekstowe {$index}: Która odpowiedź jest prawidłowa?",
            'year' => "Pytanie o rok {$index}: W którym roku?",
            'number' => "Pytanie numeryczne {$index}: Ile wynosi wynik?",
            'date_month' => "Pytanie o datę {$index}: Którego dnia i miesiąca?",
            'date_month_year' => "Pytanie o pełną datę {$index}: Kiedy to się wydarzyło?",
            default => "Pytanie {$index}",
        };
    }

    private function createAnswers(Question $question, string $questionType, int $index): void
    {
        if ($questionType === 'single_text') {
            // Create 4 answers: 1 correct, 3 incorrect
            $question->answers()->create([
                'text' => 'Odpowiedź A - Poprawna',
                'is_correct' => true,
                'order' => 1,
            ]);

            $question->answers()->create([
                'text' => 'Odpowiedź B - Niepoprawna',
                'is_correct' => false,
                'order' => 2,
            ]);

            $question->answers()->create([
                'text' => 'Odpowiedź C - Niepoprawna',
                'is_correct' => false,
                'order' => 3,
            ]);

            $question->answers()->create([
                'text' => 'Odpowiedź D - Niepoprawna',
                'is_correct' => false,
                'order' => 4,
            ]);
        } elseif ($questionType === 'year') {
            // Create 2 answers: 1 correct year, 1 incorrect
            $correctYear = 2000 + $index;
            $question->answers()->create([
                'text' => (string) $correctYear,
                'is_correct' => true,
                'order' => 1,
            ]);

            $question->answers()->create([
                'text' => 'incorrect',
                'is_correct' => false,
                'order' => 2,
            ]);
        } elseif ($questionType === 'number') {
            // Create 2 answers: 1 correct number, 1 incorrect
            $correctNumber = $index * 10;
            $question->answers()->create([
                'text' => (string) $correctNumber,
                'is_correct' => true,
                'order' => 1,
            ]);

            $question->answers()->create([
                'text' => 'incorrect',
                'is_correct' => false,
                'order' => 2,
            ]);
        } elseif ($questionType === 'date_month') {
            // Create 2 answers: 1 correct date+month, 1 incorrect
            // Format: DD.MM
            $day = str_pad($index, 2, '0', STR_PAD_LEFT);
            $month = str_pad($index, 2, '0', STR_PAD_LEFT);
            $question->answers()->create([
                'text' => "{$day}.{$month}",
                'is_correct' => true,
                'order' => 1,
            ]);

            $question->answers()->create([
                'text' => 'incorrect',
                'is_correct' => false,
                'order' => 2,
            ]);
        } elseif ($questionType === 'date_month_year') {
            // Create 2 answers: 1 correct date+month+year, 1 incorrect
            // Format: DD.MM.YYYY
            $day = str_pad($index, 2, '0', STR_PAD_LEFT);
            $month = str_pad($index, 2, '0', STR_PAD_LEFT);
            $year = 2000 + $index;
            $question->answers()->create([
                'text' => "{$day}.{$month}.{$year}",
                'is_correct' => true,
                'order' => 1,
            ]);

            $question->answers()->create([
                'text' => 'incorrect',
                'is_correct' => false,
                'order' => 2,
            ]);
        }
    }
}
