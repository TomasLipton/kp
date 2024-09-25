<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Main extends Component
{
    public function render()
    {
        return view('livewire.main', ['topics' => \App\Models\Topics::all()]);
    }
}
