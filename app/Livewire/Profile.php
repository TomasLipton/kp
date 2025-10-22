<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app-kp')]
class Profile extends Component
{
    public $socialiteUsers = [];

    public function render()
    {
        $user = auth()->user();

        $this->socialiteUsers = $user->socialiteUsers;

        return view('livewire.profile');
    }
}
