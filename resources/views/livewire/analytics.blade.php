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
                <header class="border-l-4 border-primary pl-4">
                    <div class="flex items-center gap-3">
                        @svg('lucide-bar-chart-3', 'w-6 h-6 text-primary')
                        <h2 class="text-2xl font-semibold bg-gradient-primary bg-clip-text text-transparent">
                            {{ __('Statystyki Quizów') }}
                        </h2>
                    </div>
                    <p class="mt-2 text-sm text-muted-foreground">
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
