<?php

namespace App\Livewire;

use App\Models\Quiz;
use App\Models\Topics;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class SurveyQuestion extends Component
{
    public $title = 'Post title...';
    public Topics $topic;

    public Quiz $quiz;

//    public function mount(Topics $topic, Quiz $quiz)
//    {
//        	$this->topic = $topic;
//        	$this->quiz = $quiz;
//    }
    #[Layout('layouts.app')]
    #[Title('Create Post')]
    public function render()
    {
        return view('livewire.survey-question');
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
}
