<?php

namespace App\Livewire;

use App\Models\AIQuiz;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app-kp')]
class AiPage extends Component
{
    public bool $isAdmin = false;

    public ?string $token = null;

    public AIQuiz $quiz;

    public function mount(AIQuiz $quiz): void
    {
        // Ensure the quiz belongs to the authenticated user
        if ($quiz->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this quiz');
        }

        $this->quiz = $quiz;
    }

    public function render()
    {
        return view('livewire.ai-realtime-play');
    }
}
