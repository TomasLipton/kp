@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
@endphp

<?php

use App\Models\Topics;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app-kp')] class extends Component
{
    public function with(): array
    {
        return [
            'topics' => Topics::where('isVisibleToPublic', true)->get(),
        ];
    }
}; ?>

@assets
@vite(['resources/css/main.scss'])
@endassets

<div>

    {{-- Hero Section with animated background --}}
    <div class="mt-8 mb-12 text-center relative px-4">


        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 border border-primary/20 mb-6 backdrop-blur-sm">
            @svg('lucide-book-open', 'w-4 h-4 text-primary')
            <span class="text-sm font-medium text-primary">{{ __('All Available Topics') }}</span>
        </div>

        <h1 class="text-5xl md:text-6xl font-bold bg-gradient-primary bg-clip-text text-transparent mb-6 tracking-tight px-2">
            {{ __('Quiz Topics') }}
        </h1>
        <p class="text-xl text-muted-foreground max-w-2xl mx-auto leading-relaxed">
            {{ __('Choose a topic to start your journey through Polish history and culture') }}
        </p>

        {{-- Stats badges --}}
        <div class="flex flex-wrap justify-center gap-6 mt-8">
            <div class="flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-card backdrop-blur-sm border border-white/40">
                @svg('lucide-layers', 'w-5 h-5 text-primary')
                <span class="text-sm font-semibold">{{ $topics->count() }} {{ __('Topics') }}</span>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-card backdrop-blur-sm border border-white/40">
                @svg('lucide-help-circle', 'w-5 h-5 text-primary')
                <span class="text-sm font-semibold">{{ $topics->sum(fn($t) => $t->questions()->count()) }} {{ __('Questions') }}</span>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-card backdrop-blur-sm border border-white/40">
                @svg('lucide-sparkles', 'w-5 h-5 text-primary')
                <span class="text-sm font-semibold">100% {{ __('Free') }}</span>
            </div>
        </div>
    </div>

    {{-- Topics Section --}}
    <div class="mt-14 relative">
        <!-- Decorative frame -->
        <div class="absolute inset-0 rounded-3xl border-2 border-primary/20 pointer-events-none"></div>
        <div class="absolute -inset-1 rounded-3xl border border-primary/10 pointer-events-none"></div>

        <div class="relative p-8 md:p-10">
            {{-- Topic Cards - Image Focused --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @foreach($topics as $topic)
                    <a href="/{{$topic->slug}}" wire:navigate wire:navigate.hover
                       class="group bg-gray-50 dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-[1.02] border border-gray-200 dark:border-gray-700 p-3">

                        {{-- Image Section --}}
                        <div class="relative overflow-hidden h-48 rounded-lg">
                            <img src="{{url('storage/' . $topic->picture)}}" alt="{{$topic->name_pl}}"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">

                            {{-- Badges on Image --}}
                            <div class="absolute top-3 right-3 flex flex-col gap-2 items-end">
                                {{-- Question Count Badge --}}
                                <span class="px-3 py-1.5 rounded-full bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm text-xs font-semibold border border-primary/30 text-foreground">
                                    {{$topic->questions()->count()}}
                                    @php
                                        $count = $topic->questions()->count();
                                    @endphp
                                    @if($count === 1)
                                        {{ __('app.question') }}
                                    @elseif($count % 10 >= 2 && $count % 10 <= 4 && ($count % 100 < 10 || $count % 100 >= 20))
                                        {{ __('app.questions_few') }}
                                    @else
                                        {{ __('app.questions_many') }}
                                    @endif
                                </span>

                                {{-- Difficulty Badge --}}
                                @php
                                    $difficultyColors = [
                                        'easy' => 'border-green-500/50 text-green-700 dark:text-green-400',
                                        'medium' => 'border-orange-500/50 text-orange-700 dark:text-orange-400',
                                        'hard' => 'border-red-500/50 text-red-700 dark:text-red-400',
                                    ];
                                    $difficulty = $topic->difficulty ?? 'medium';
                                @endphp
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm text-xs font-semibold border {{ $difficultyColors[$difficulty] }}">
                                    @svg('lucide-zap', 'w-3 h-3')
                                    <span class="hidden sm:inline">{{ __('app.difficulty_' . $difficulty) }}</span>
                                </span>
                            </div>
                        </div>

                        {{-- Content Section Below Image --}}
                        <div class="p-4">
                            <h3 class="text-lg font-bold mb-3 text-foreground group-hover:text-primary transition-colors line-clamp-2">
                                @switch(LaravelLocalization::getCurrentLocale())
                                    @case('ru')
                                        {{trim($topic->name_ru ?? $topic->name_pl)}}
                                        @break
                                    @case('uk')
                                        {{trim($topic->name_uk ?? $topic->name_pl)}}
                                        @break
                                    @case('be')
                                        {{trim($topic->name_be ?? $topic->name_pl)}}
                                        @break
                                    @default
                                        {{trim($topic->name_pl)}}
                                @endswitch
                            </h3>

                            <div class="flex items-center gap-2 text-sm font-medium text-primary">
                                <span>{{ __('app.start_quiz') }}</span>
                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Hover Border Effect --}}
                        <div class="absolute inset-0 rounded-xl ring-2 ring-primary/0 group-hover:ring-primary/50 transition-all duration-300 pointer-events-none"></div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

</div>
