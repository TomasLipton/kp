<?php

namespace App\Livewire;

use App\Models\Quiz;
use Livewire\Component;

class SurveyResults extends Component
{

    public Quiz $quiz ;

    public function mount()
    {
//        $this->
    }

    public function render()
    {
        return view('livewire.survey-results');
    }
}
