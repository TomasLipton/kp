<?php

namespace App\Livewire;

use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\Topics;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class SurveyQuestion extends Component
{
    public $title = 'Post title...';
    public Topics $topic;

    public Quiz $quiz;

    public Question $question;

    public $chosenAnswer;

    #[Layout('layouts.app')]
    #[Title('Create Post')]
    public function render()
    {
        return view('livewire.survey-question');
    }

    public function mount()
    {
        $answeredQuestionIds = QuizAnswer::where('quiz_id', $this->quiz->id)->with('questionAnswer.question')->get()->pluck('questionAnswer.question.id')->toArray();

        $nextQuestion = $this->topic->questions()
            ->whereNotIn('id', $answeredQuestionIds)
            ->inRandomOrder()
            ->first();

        if (!$nextQuestion) {
            $this->finish();
            return;
        }

        $this->question = $nextQuestion;
    }

    public function testClick()
    {
        $this->title = 'Post title changed...';
    }

    public function finish()
    {
        $this->quiz->is_completed = true;
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

//        $this->question = $this->topic->questions()->inRandomOrder()->first();
//        $this->chosenAnswer = null;

//        if ($this->topic->questions()->count() == $this->quiz->answers()->count()) {
//dd('finish');
//            $this->finish();
//        }
//        dd($answer);
    }

    public function nextQuestion()
    {

        $answeredQuestionIds = QuizAnswer::where('quiz_id', $this->quiz->id)->with('questionAnswer.question')->get()->pluck('questionAnswer.question.id')->toArray();

        $nextQuestion = $this->topic->questions()
            ->whereNotIn('id', $answeredQuestionIds)
            ->inRandomOrder()
            ->first();

        if (!$nextQuestion) {
            $this->finish();
            return;
        }

        $this->question = $nextQuestion;
        $this->chosenAnswer = null;
    }
}
