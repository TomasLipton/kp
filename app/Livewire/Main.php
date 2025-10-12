<?php

namespace App\Livewire;

use App\Models\Topics;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app-kp')]
class Main extends Component
{
    public function render()
    {
        return view('livewire.main', ['topics' => Topics::where('isVisibleToPublic', true)->get()]);
    }
}
