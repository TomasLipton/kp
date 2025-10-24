<?php

namespace App\Livewire;

use App\Models\QuestionReport;
use Livewire\Component;

class ReportQuestion extends Component
{
    public $questionId;
    public $message = '';

    public function mount($questionId = null)
    {
        $this->questionId = $questionId;
    }

    public function submit()
    {
        $this->validate([
            'message' => 'required|min:10|max:500',
        ], [
            'message.required' => 'Proszę opisać problem z pytaniem',
            'message.min' => 'Opis musi zawierać co najmniej 10 znaków',
            'message.max' => 'Opis nie może przekraczać 500 znaków',
        ]);

        QuestionReport::create([
            'question_id' => $this->questionId,
            'message' => $this->message,
            'user_id' => auth()->id(),
        ]);

        $this->reset('message');
        $this->resetErrorBag();

        // Показать уведомление об успехе
        session()->flash('message', 'Zgłoszenie zostało wysłane. Dziękujemy!');

        // Закрыть модалку через Alpine.js
        $this->dispatch('close-modal', 'report-question');
    }

    public function render()
    {
        return view('livewire.report-question');
    }
}
