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

    <div class="mt-14 overflow-hidden bg-gradient-card backdrop-blur-sm shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] border border-white/40 rounded-lg pb-32 relative
         before:absolute before:inset-0 before:rounded-lg before:p-[1px] before:bg-gradient-to-br before:from-white/50 before:via-white/20 before:to-transparent before:-z-10">
        <div class="text-center mb-8 mt-8">
            <h1 class="text-3xl md:text-4xl font-bold bg-gradient-primary bg-clip-text text-transparent mb-4">
                {{ __('Quiz Topics') }}
            </h1>
            <p class="text-lg text-muted-foreground">
                {{ __('Choose a topic to start your journey through Polish history and culture') }}
            </p>
        </div>

        <section class="hero-section ">
            <div class="card-grid">
                @foreach($topics as $topic)
                    <a class="category-card
                rounded-lg
                 " href="/{{$topic->slug}}" wire:navigate wire:navigate.hover>
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
                        <div class="absolute inset-0 border-2 scale-105 border-primary/0 hover:border-primary/50 transition-colors duration-300 rounded-lg"></div>

                    </a>
                @endforeach
            </div>
        </section>

    </div>


</div>
