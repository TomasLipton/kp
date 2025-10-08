<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use function Livewire\Volt\layout;

new #[Layout('layouts.app-kp')]
class extends Component {
    //
}; ?>

<div>
    <div class="py-12">
        <div class=" mx-auto  space-y-6">

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('Statystyki Quizów') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Przeglądaj wyniki swoich quizów, w tym poprawne i niepoprawne odpowiedzi oraz średni czas trwania.') }}
                    </p>
                </header>

                <div class="mt-6 space-y-6">
                    @livewire(\App\Livewire\StatsOverview::class)
                </div>
                <div class="mt-5">
                    @livewire(\App\Livewire\QuizAnswersChart::class)
                </div>
            </div>
        </div>
    </div>
</div>
