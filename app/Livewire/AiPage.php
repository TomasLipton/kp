<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app-kp')]
class AiPage extends Component
{
    public function render()
    {
        abort(404);
        return view('livewire.ai-page');
    }
}
