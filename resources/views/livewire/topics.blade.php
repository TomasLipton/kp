@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
@endphp

<?php

use App\Models\Topics;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app-kp')] class extends Component
{
    public function with(): array
    {
        return [
            'topics' => Topics::all()
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

    {{-- Topics Grid --}}
    <div class="overflow-hidden bg-gradient-card backdrop-blur-sm shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] border border-white/40 rounded-2xl  relative
         before:absolute before:inset-0 before:rounded-2xl before:p-[1px] before:bg-gradient-to-br before:from-white/50 before:via-white/20 before:to-transparent before:-z-10">

        <section class="hero-section pt-8">
            <div class="card-grid">
                @foreach($topics as $topic)
                    <a class="category-card rounded-lg group" href="/{{$topic->slug}}" wire:navigate wire:navigate.hover>
                        <div class="card__background" style="background-image: url({{url('storage/' . $topic->picture)}})"></div>
                        <div class="card__content">
                            <p class="card__category">
                                {{$topic->questions()->count()}}
                                @php
                                    $count = $topic->questions()->count();
                                @endphp
                                @if($count == 1)
                                    {{ __('app.question') }}
                                @elseif($count % 10 >= 2 && $count % 10 <= 4 && ($count % 100 < 10 || $count % 100 >= 20))
                                    {{ __('app.questions_few') }}
                                @else
                                    {{ __('app.questions_many') }}
                                @endif
                            </p>
                            <h3 class="card__heading">
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
                        </div>
                        <div class="absolute inset-0 border-2 scale-105 border-primary/0 group-hover:border-primary/50 transition-all duration-300 rounded-lg group-hover:shadow-[0_0_30px_rgba(var(--primary-rgb),0.3)]"></div>
                    </a>
                @endforeach
            </div>
        </section>
    </div>

    {{-- CTA Banner with gradient and glow effects --}}
    <div class="mt-16 mb-16 relative">
        {{-- Glow effect background --}}
        <div class="absolute inset-0 bg-gradient-to-r from-primary/20 via-secondary/20 to-primary/20 blur-3xl -z-10 rounded-3xl"></div>

        <div class="overflow-hidden bg-gradient-card backdrop-blur-sm shadow-[0_20px_60px_-10px_rgba(0,0,0,0.2)] border border-white/40 rounded-2xl p-12 md:p-16 relative
             before:absolute before:inset-0 before:rounded-2xl before:p-[1px] before:bg-gradient-to-br before:from-white/60 before:via-white/30 before:to-transparent before:-z-10">

            {{-- Decorative corner elements --}}
            <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-primary/20 to-transparent rounded-bl-full -z-10"></div>
            <div class="absolute bottom-0 left-0 w-40 h-40 bg-gradient-to-tr from-secondary/20 to-transparent rounded-tr-full -z-10"></div>

            <div class="text-center max-w-3xl mx-auto relative z-10">
                {{-- Icon badge --}}
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-primary mb-6 shadow-glow">
                    @guest
                        @svg('lucide-rocket', 'w-8 h-8 text-white')
                    @else
                        @svg('lucide-zap', 'w-8 h-8 text-white')
                    @endguest
                </div>

                <h2 class="text-4xl md:text-5xl font-bold bg-gradient-primary bg-clip-text text-transparent mb-6 leading-tight">
                    @guest
                        {{ __('Ready to start learning?') }}
                    @else
                        {{ __('Continue your learning journey') }}
                    @endguest
                </h2>

                <p class="text-xl text-muted-foreground mb-10 leading-relaxed">
                    @guest
                        {{ __('Create a free account to track your progress and access all quiz features') }}
                    @else
                        {{ __('Choose any topic above and start practicing for your Polish Card exam') }}
                    @endguest
                </p>

                @guest
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="{{ route('register') }}" wire:navigate
                           class="group inline-flex items-center gap-2 px-8 py-4 bg-gradient-primary text-white font-semibold rounded-xl
                                  shadow-glow hover:shadow-[0_20px_60px_-15px_hsl(var(--primary))] transition-all duration-300 hover:scale-105 hover:-translate-y-1">
                            {{ __('Create a free account') }}
                            @svg('lucide-user-plus', 'w-5 h-5 group-hover:rotate-12 transition-transform')
                        </a>
                        <a href="{{ route('login') }}" wire:navigate
                           class="group inline-flex items-center gap-2 px-8 py-4 bg-white text-primary font-semibold rounded-xl border-2 border-primary
                                  hover:bg-primary/5 transition-all duration-300 hover:scale-105 hover:-translate-y-1 hover:shadow-lg">
                            {{ __('Already have an account?') }}
                            @svg('lucide-log-in', 'w-5 h-5 group-hover:translate-x-1 transition-transform')
                        </a>
                    </div>
                @else
                    <a href="{{ route('dashboard') }}" wire:navigate
                       class="group inline-flex items-center gap-2 px-8 py-4 bg-gradient-primary text-white font-semibold rounded-xl
                              shadow-glow hover:shadow-[0_20px_60px_-15px_hsl(var(--primary))] transition-all duration-300 hover:scale-105 hover:-translate-y-1">
                        {{ __('Go to Dashboard') }}
                        @svg('lucide-arrow-right', 'w-5 h-5 group-hover:translate-x-2 transition-transform')
                    </a>
                @endguest

                {{-- Trust indicators --}}
                <div class="flex flex-wrap justify-center gap-8 mt-12 pt-8 border-t border-border/30">
                    <div class="flex items-center gap-2 text-sm text-muted-foreground">
                        @svg('lucide-shield-check', 'w-4 h-4 text-primary')
                        <span>{{ __('Secure & Safe') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-muted-foreground">
                        @svg('lucide-infinity', 'w-4 h-4 text-primary')
                        <span>{{ __('Unlimited Access') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-muted-foreground">
                        @svg('lucide-heart', 'w-4 h-4 text-primary')
                        <span>{{ __('Always Free') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
