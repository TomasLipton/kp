<?php

namespace App\Livewire;

use App\Models\Quiz;
use App\Models\Topics;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Create Post')]
class StartSurvey extends Component
{
    public Topics $topic;

    public string $surveyMode= 'Wszystkie pytania';

    public function render()
    {
        return view('livewire.start-survey');
    }

    public function startSurvey()
    {
        $type = [
            'Wszystkie pytania' => 'all_questions',
            '10 pytań' => '10_questions'
        ];

        $quiz = new Quiz([
            'uuid' => Str::uuid(),
            'type' => $type[$this->surveyMode],
            'questions_amount' => 10,
            'user_id' => auth()->id(),
            'topics_id' => $this->topic->id,
        ]);

        $quiz->save();

        return $this->redirect($this->topic->slug . '/' . $quiz->uuid, navigate: true);

    }

    public function setMode( $mode)
    {
        $this->surveyMode = $mode;
    }
}
