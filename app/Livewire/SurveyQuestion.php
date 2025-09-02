<?php

namespace App\Livewire;

use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\Topics;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

class SurveyQuestion extends Component
{
    public $title = 'Post title...';

    public Topics $topic;

    public Quiz $quiz;

    public Question $question;

    public Collection $questionAnswers;

    public $questionsAnswered = 0;

    public $chosenAnswer;

    #[Layout('layouts.app-kp')]
    public function render()
    {
        return view('livewire.survey-question')
            ->title($this->topic->name_pl.($this->quiz->completed_at ? ' - Wyniki testu' : null));
    }

    public function mount()
    {
        $answeredQuestionIds = QuizAnswer::where('quiz_id', $this->quiz->id)->with('questionAnswer.question')->get()->pluck('questionAnswer.question.id')->toArray();

        $nextQuestion = $this->topic->questions()
            ->whereNotIn('id', $answeredQuestionIds)
            ->inRandomOrder()
            ->with([
                'answers' => function ($query) {
                    $query->inRandomOrder();
                },
            ])
            ->first();

        $this->questionsAnswered = count($answeredQuestionIds);

        if ($this->quiz->completed_at) {
            return;
        }

        if (! $nextQuestion) {
            $this->finish();

            return;
        }

        $this->question = $nextQuestion;

        $this->questionAnswers = $nextQuestion->answers;
    }

    public function testClick()
    {
        $this->title = 'Post title changed...';
    }

    public function finish()
    {
        $this->quiz->completed_at = now();
        $this->quiz->save();
    }

    public function submitAnswer(QuestionAnswer $answer)
    {
        if ($this->chosenAnswer) {
            return;
        }
        $this->chosenAnswer = $answer;

        (new QuizAnswer([
            'quiz_id' => $this->quiz->id,
            'question_answer_id' => $answer->id,
        ]))->save();

    }

    public function submitYear($value)
    {
        if ($this->chosenAnswer || strlen($value) < 3 || strlen($value) > 4) {
            return;
        }
        $this->chosenAnswer = $this->question->answers->where('text', $value)->first();

        if (! $this->chosenAnswer) {
            $this->chosenAnswer = $this->question->answers->where('is_correct', 0)->first();
        }

        (new QuizAnswer([
            'quiz_id' => $this->quiz->id,
            'question_answer_id' => $this->chosenAnswer->id,
        ]))->save();
    }

    public function submitDateMonth($date, $month)
    {

        if ($this->chosenAnswer || strlen($date) > 2 || strlen($date) < 1) {
            return;
        }

        $formattedMonth = str_pad($month, 2, '0', STR_PAD_LEFT);

        $this->chosenAnswer = $this->question->answers->where('text', $date.'.'.$formattedMonth)->first();

        if (! $this->chosenAnswer) {
            $this->chosenAnswer = $this->question->answers->where('is_correct', 0)->first();
        }

        (new QuizAnswer([
            'quiz_id' => $this->quiz->id,
            'question_answer_id' => $this->chosenAnswer->id,
        ]))->save();
    }

    public function submitAnswerByOrder($number)
    {
        if ($this->chosenAnswer) {
            return;
        }

        $this->chosenAnswer = $this->question->answers->where('order', $number)->first();

        if (! $this->chosenAnswer) {
            return;
        }

        (new QuizAnswer([
            'quiz_id' => $this->quiz->id,
            'question_answer_id' => $this->chosenAnswer->id,
        ]))->save();

    }

    public function nextQuestion()
    {

        if (! $this->chosenAnswer) {
            return;
        }
        $answeredQuestionIds = QuizAnswer::where('quiz_id', $this->quiz->id)->with('questionAnswer.question')->get()->pluck('questionAnswer.question.id')->toArray();

        $nextQuestion = $this->topic->questions()
            ->whereNotIn('id', $answeredQuestionIds)
            ->inRandomOrder()
            ->first();

        $this->questionsAnswered = count($answeredQuestionIds);

        if (! $nextQuestion) {
            $this->finish();

            return;
        }

        $this->question = $nextQuestion;
        $this->questionAnswers = $nextQuestion->answers;

        $this->chosenAnswer = null;

        $this->dispatch('clear-input');
    }
}
