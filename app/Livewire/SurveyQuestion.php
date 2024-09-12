<?php

namespace App\Livewire;

use App\Models\Topics;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class SurveyQuestion extends Component
{
    public $title = 'Post title...';
    public Topics $topic;

    public function mount(Topics $topic)
    {
        	$this->topic = $topic;
    }
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
}
