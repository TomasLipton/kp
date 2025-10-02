@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
@endphp

@assets
@vite(['resources/css/main.scss'])

@endassets

<div>

    {{-- Hero Section --}}
    <div class="mt-8 mb-8 text-center">
        <h1 class="text-4xl md:text-6xl font-bold bg-gradient-primary bg-clip-text text-transparent mb-4">
            QuizPolaka
        </h1>
        <p class="text-xl md:text-2xl text-foreground/80 mb-6">
            {{ __('Prepare for the Polish Card exam') }}
        </p>
        <p class="text-base md:text-lg text-muted-foreground max-w-2xl mx-auto mb-8">
            {{ __('Check your knowledge of Polish history, culture and language. Solve interactive tests and increase your chances of obtaining the Polish Card.') }}
        </p>

        {{-- Stats Section --}}
        <div class="flex flex-wrap justify-center gap-8 mb-8">
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-primary">{{$topics->count()}}</div>
                <div class="text-sm text-muted-foreground">{{ __('Topics') }}</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-primary">{{$topics->sum(fn($t) => $t->questions()->count())}}</div>
                <div class="text-sm text-muted-foreground">{{ __('Questions') }}</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-primary">100%</div>
                <div class="text-sm text-muted-foreground">{{ __('Free') }}</div>
            </div>
        </div>

        {{-- CTA Buttons --}}
        <div class="flex flex-wrap justify-center gap-4 mb-8">
            <a href="{{ route('topics') }}" wire:navigate
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-primary text-white font-semibold rounded-lg
                      shadow-glow hover:shadow-[0_15px_50px_-15px_hsl(var(--primary))] transition-all duration-300 hover:scale-105">
                {{ __('Browse topics') }}
                @svg('lucide-arrow-right', 'w-5 h-5')
            </a>
            @guest
                <a href="{{ route('register') }}" wire:navigate
                   class="inline-flex items-center gap-2 px-6 py-3 bg-white text-primary font-semibold rounded-lg border-2 border-primary
                          hover:bg-primary/5 transition-all duration-300 hover:scale-105">
                    {{ __('Create a free account') }}
                    @svg('lucide-user-plus', 'w-5 h-5')
                </a>
            @endguest
        </div>
    </div>

    <div class="mt-14 overflow-hidden bg-gradient-card backdrop-blur-sm shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] border border-white/40 rounded-lg pb-8 relative
         before:absolute before:inset-0 before:rounded-lg before:p-[1px] before:bg-gradient-to-br before:from-white/50 before:via-white/20 before:to-transparent before:-z-10">
        <div class="text-center mb-8 mt-8">
            <h2 class="text-3xl md:text-4xl font-bold bg-gradient-primary bg-clip-text text-transparent mb-4">
                {{ __('Quiz Topics') }}
            </h2>
            <p class="text-lg text-muted-foreground">
                {{ __('Choose a topic to start your journey through Polish history and culture') }}
            </p>
        </div>

        <section class="hero-section ">
            <div class="card-grid">
                @foreach($topics->take(3) as $topic)
                    <a class="category-card rounded-lg" href="/{{$topic->slug}}" wire:navigate wire:navigate.hover>
                        <div class="card__background" style="background-image: url({{url('storage/' . $topic->picture)}})"></div>
                        <div class="card__content">
                            <p class="card__category">
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

                {{-- View All Topics Card --}}
                <a class="category-card rounded-lg" href="{{ route('topics') }}" wire:navigate wire:navigate.hover>
                    <div class="card__background" style="background: linear-gradient(135deg, hsl(var(--primary)) 0%, hsl(var(--primary) / 0.8) 100%)"></div>
                    <div class="card__content flex flex-col items-center justify-center h-full text-center">
                        <h3 class="card__heading text-2xl mb-2">
                            {{ __('View all topics') }}
                        </h3>
                        <p class="card__category">
                            {{$topics->count()}} {{ __('available topics') }}
                        </p>
                    </div>
                    <div class="absolute inset-0 border-2 scale-105 border-primary/0 hover:border-primary/50 transition-colors duration-300 rounded-lg"></div>
                </a>
            </div>
        </section>

    </div>

    {{-- How It Works Section --}}
    <div class="mt-16 mb-16">
        <h2 class="text-3xl md:text-4xl font-bold text-center bg-gradient-primary bg-clip-text text-transparent mb-12">
            {{ __('How does it work?') }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-primary/10 flex items-center justify-center">
                    <span class="text-3xl font-bold text-primary">1</span>
                </div>
                <h3 class="text-xl font-semibold mb-2">{{ __('Choose a topic') }}</h3>
                <p class="text-muted-foreground">
                    {{ __('Choose one of the available topics: history, culture, geography or Polish language') }}
                </p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-primary/10 flex items-center justify-center">
                    <span class="text-3xl font-bold text-primary">2</span>
                </div>
                <h3 class="text-xl font-semibold mb-2">{{ __('Solve the quiz') }}</h3>
                <p class="text-muted-foreground">
                    {{ __('Answer questions and get instant feedback after each answer') }}
                </p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-primary/10 flex items-center justify-center">
                    <span class="text-3xl font-bold text-primary">3</span>
                </div>
                <h3 class="text-xl font-semibold mb-2">{{ __('Track your progress') }}</h3>
                <p class="text-muted-foreground">
                    {{ __('See your results and identify areas that need more study') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Features Section --}}
    <div class="mt-16 mb-16 overflow-hidden bg-gradient-card backdrop-blur-sm shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] border border-white/40 rounded-lg p-8 md:p-12 relative
         before:absolute before:inset-0 before:rounded-lg before:p-[1px] before:bg-gradient-to-br before:from-white/50 before:via-white/20 before:to-transparent before:-z-10">
        <h2 class="text-3xl md:text-4xl font-bold text-center bg-gradient-primary bg-clip-text text-transparent mb-12">
            {{ __('Why choose QuizPolaka?') }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="flex gap-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2">{{ __('Current exam questions') }}</h3>
                    <p class="text-muted-foreground">
                        {{ __('All questions are based on the official Polish Card exam program') }}
                    </p>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2">{{ __('Instant feedback') }}</h3>
                    <p class="text-muted-foreground">
                        {{ __('Find out immediately if your answer is correct and learn from mistakes') }}
                    </p>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2">{{ __('Learn at your own pace') }}</h3>
                    <p class="text-muted-foreground">
                        {{ __('No time limit - solve quizzes whenever you want and as many times as you want') }}
                    </p>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2">{{ __('Available everywhere') }}</h3>
                    <p class="text-muted-foreground">
                        {{ __('Use the platform on your computer, tablet or smartphone - wherever you want') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- FAQ Section --}}
    <div class="mt-16 mb-16">
        <h2 class="text-3xl md:text-4xl font-bold text-center bg-gradient-primary bg-clip-text text-transparent mb-12">
            {{ __('Frequently asked questions') }}
        </h2>
        <div class="max-w-3xl mx-auto space-y-4" x-data="{ open: null }">
            {{-- FAQ Item 1 --}}
            <div class="overflow-hidden bg-gradient-card backdrop-blur-sm shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] border border-white/40 rounded-lg
                        before:absolute before:inset-0 before:rounded-lg before:p-[1px] before:bg-gradient-to-br before:from-white/50 before:via-white/20 before:to-transparent before:-z-10 relative">
                <button @click="open = open === 1 ? null : 1"
                        class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-primary/5 transition-colors">
                    <span class="font-semibold text-foreground">{{ __('What is the Polish Card?') }}</span>
                    <svg class="w-5 h-5 text-primary transition-transform duration-200" :class="{ 'rotate-180': open === 1 }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open === 1" x-collapse class="px-6 pb-4">
                    <p class="text-sm text-muted-foreground">
                        {{ __('The Polish Card is a document confirming membership in the Polish Nation, granted to people who do not have Polish citizenship and who declare their affiliation with the Polish Nation.') }}
                    </p>
                </div>
            </div>

            {{-- FAQ Item 2 --}}
            <div class="overflow-hidden bg-gradient-card backdrop-blur-sm shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] border border-white/40 rounded-lg
                        before:absolute before:inset-0 before:rounded-lg before:p-[1px] before:bg-gradient-to-br before:from-white/50 before:via-white/20 before:to-transparent before:-z-10 relative">
                <button @click="open = open === 2 ? null : 2"
                        class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-primary/5 transition-colors">
                    <span class="font-semibold text-foreground">{{ __('How does the qualification interview proceed?') }}</span>
                    <svg class="w-5 h-5 text-primary transition-transform duration-200" :class="{ 'rotate-180': open === 2 }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open === 2" x-collapse class="px-6 pb-4">
                    <p class="text-sm text-muted-foreground">
                        {{ __('The qualification interview includes questions on Polish history, culture and language. The candidate must demonstrate basic knowledge of these areas and the ability to communicate in Polish.') }}
                    </p>
                </div>
            </div>

            {{-- FAQ Item 3 --}}
            <div class="overflow-hidden bg-gradient-card backdrop-blur-sm shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] border border-white/40 rounded-lg
                        before:absolute before:inset-0 before:rounded-lg before:p-[1px] before:bg-gradient-to-br before:from-white/50 before:via-white/20 before:to-transparent before:-z-10 relative">
                <button @click="open = open === 3 ? null : 3"
                        class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-primary/5 transition-colors">
                    <span class="font-semibold text-foreground">{{ __('Is QuizPolaka free?') }}</span>
                    <svg class="w-5 h-5 text-primary transition-transform duration-200" :class="{ 'rotate-180': open === 3 }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open === 3" x-collapse class="px-6 pb-4">
                    <p class="text-sm text-muted-foreground">
                        {{ __('Yes! QuizPolaka is 100% free. All quizzes, questions and features are available at no cost. Just create a free account to start learning.') }}
                    </p>
                </div>
            </div>

            {{-- FAQ Item 4 --}}
            <div class="overflow-hidden bg-gradient-card backdrop-blur-sm shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] border border-white/40 rounded-lg
                        before:absolute before:inset-0 before:rounded-lg before:p-[1px] before:bg-gradient-to-br before:from-white/50 before:via-white/20 before:to-transparent before:-z-10 relative">
                <button @click="open = open === 4 ? null : 4"
                        class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-primary/5 transition-colors">
                    <span class="font-semibold text-foreground">{{ __('How many times can I repeat quizzes?') }}</span>
                    <svg class="w-5 h-5 text-primary transition-transform duration-200" :class="{ 'rotate-180': open === 4 }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open === 4" x-collapse class="px-6 pb-4">
                    <p class="text-sm text-muted-foreground">
                        {{ __('You can repeat quizzes an unlimited number of times! Learning through repetition is one of the most effective ways to prepare for the exam. The more you practice, the better you will remember the material.') }}
                    </p>
                </div>
            </div>

            {{-- FAQ Item 5 --}}
{{--            <div class="overflow-hidden bg-gradient-card backdrop-blur-sm shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] border border-white/40 rounded-lg--}}
{{--                        before:absolute before:inset-0 before:rounded-lg before:p-[1px] before:bg-gradient-to-br before:from-white/50 before:via-white/20 before:to-transparent before:-z-10 relative">--}}
{{--                <button @click="open = open === 5 ? null : 5"--}}
{{--                        class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-primary/5 transition-colors">--}}
{{--                    <span class="font-semibold text-foreground">{{ __('Where do the questions in the quizzes come from?') }}</span>--}}
{{--                    <svg class="w-5 h-5 text-primary transition-transform duration-200" :class="{ 'rotate-180': open === 5 }"--}}
{{--                         fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
{{--                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>--}}
{{--                    </svg>--}}
{{--                </button>--}}
{{--                <div x-show="open === 5" x-collapse class="px-6 pb-4">--}}
{{--                    <p class="text-sm text-muted-foreground">--}}
{{--                        {{ __('All questions are based on the official Polish Card exam program and cover the most important issues in history, culture, geography and the Polish language, which are most often discussed during the qualification interview.') }}--}}
{{--                    </p>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </div>

    {{-- Footer --}}
    <footer class="mt-16 bg-gradient-card backdrop-blur-sm border border-white/40 rounded-t-lg overflow-hidden relative
                   shadow-[0_-10px_40px_-10px_rgba(0,0,0,0.15)]
                   before:absolute before:inset-0 before:rounded-t-3xl before:p-[1px] before:bg-gradient-to-br before:from-white/50 before:via-white/20 before:to-transparent before:-z-10">
        <div class="container mx-auto px-4 py-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                {{-- Left Column --}}
                <div class="flex flex-col items-center md:items-start gap-4">
                    <div class="inline-flex items-center gap-2 px-2.5 py-1.5 rounded-full bg-primary/10 border border-primary/20">
                        @svg('lucide-shield-check', 'w-3.5 h-3.5 text-primary')
                        <span class="text-[10px] font-medium text-foreground">{{ __('Secure payments via Stripe') }}</span>
                    </div>

                    <div class="text-center md:text-left space-y-2">
                        <p class="text-xs text-muted-foreground">
                            © {{ date('Y') }} QuizPolaka. {{ __('All rights reserved.') }}
                        </p>
                        <div class="flex flex-wrap justify-center md:justify-start gap-4 text-xs">
                            <a href="#" class="text-muted-foreground hover:text-primary transition-colors">
                                {{ __('Privacy Policy') }}
                            </a>
                            <a href="#" class="text-muted-foreground hover:text-primary transition-colors">
                                {{ __('Terms of Service') }}
                            </a>
                            <a href="#" class="text-muted-foreground hover:text-primary transition-colors">
                                {{ __('Cookies') }}
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="flex flex-wrap justify-center md:justify-end gap-3">
                    <a href="{{ route('topics') }}" wire:navigate
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-primary text-white text-sm font-semibold rounded-lg
                              shadow-glow hover:shadow-[0_15px_50px_-15px_hsl(var(--primary))] transition-all duration-300 hover:scale-105">
                        {{ __('Browse topics') }}
                        @svg('lucide-arrow-right', 'w-4 h-4')
                    </a>
                    @guest
                        <a href="{{ route('register') }}" wire:navigate
                           class="inline-flex items-center gap-2 px-4 py-2 bg-white text-primary text-sm font-semibold rounded-lg border-2 border-primary
                                  hover:bg-primary/5 transition-all duration-300 hover:scale-105">
                            {{ __('Create a free account') }}
                            @svg('lucide-user-plus', 'w-4 h-4')
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </footer>

</div>
