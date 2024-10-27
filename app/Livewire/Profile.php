<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app-kp')]

class Profile extends Component
{
    public function render()
    {
        return view('livewire.profile');
    }
}
