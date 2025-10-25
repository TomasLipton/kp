<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app-kp')] class extends Component
{
    //
}; ?>

@assets
@vite(['resources/css/main.scss'])
@endassets

<div>
    <div class="max-w-4xl mx-auto py-12 px-4">
        <h1 class="text-4xl md:text-5xl font-bold text-center bg-gradient-primary bg-clip-text text-transparent mb-8">
            Regulamin
        </h1>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-8">
            <div class="prose prose-gray dark:prose-invert max-w-none">
                <p class="text-muted-foreground text-center">
                    Treść regulaminu będzie dostępna wkrótce.
                </p>
            </div>
        </div>
    </div>
</div>
