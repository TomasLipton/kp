@assets
@vite('resources/css/start-survey.scss')
@endassets

<div>
    <div class="m-2">
        <a
            href="{{ route('dashboard') }}"
            wire:navigate
            class="
                inline-flex items-center justify-center gap-2
                whitespace-nowrap rounded-md text-sm font-medium ring-offset-background
                transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-ring
                focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50
                bg-primary/10 hover:text-accent-foreground h-10 px-4 py-2"
        >
            <i data-lucide="arrow-left " class="4-5 h-4"></i>
            {{ __('app.back_to_topics') }}
        </a>
    </div>

    <div class="min-h-screen_   ">

        <div class="container_ max-w-[1200px]_ mx-auto ">
            <div class="overflow-hidden bg-card shadow-card border border-border/50 rounded-lg bg-card/80 backdrop-blur-sm border-b ">
                <!-- Quiz Image -->
                <div class="relative h-48 overflow-hidden">
                    <img
                        id="quiz-image"
                        src="{{url('storage/' . $topic->picture)}}"
                        alt="{{ __('app.quiz_title_alt') }}"
                        class="w-full h-full object-cover"
                    />
                    <div class="absolute top-4 right-4">
                        <span
                            id="difficulty-badge"
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-500/90 text-white border-0 backdrop-blur-sm"
                        >
                            {{ __('app.difficulty_medium') }}
                        </span>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="p-6">
                    <!-- Quiz Title -->
                    <h1 id="quiz-title" class="text-2xl md:text-3xl font-bold text-foreground mb-3">
                        {{$topic->name_pl}}
                    </h1>

                    <!-- Description -->
                    <p id="quiz-description" class="text-muted-foreground leading-relaxed mb-6">
                        {{$topic->description_pl}}                    </p>

                    <!-- Stats Row -->
                    <div class="flex items-center gap-4 mb-6 text-sm text-muted-foreground">
                        <div class="flex items-center gap-1">
                            <i data-lucide="book-open" class="w-4 h-4"></i>
                            <span id="quiz-questions">
                                @php
                                    $count = $topic->questions()->count();
                                    $questionText = $count == 1 ? __('app.question') :
                                        (($count % 10 >= 2 && $count % 10 <= 4 && ($count % 100 < 10 || $count % 100 >= 20)) ? __('app.questions_few') : __('app.questions_many'));
                                @endphp
                                {{ $count }} {{ $questionText }}
                            </span>
                        </div>
                        <div class="flex items-center gap-1">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                            <span id="quiz-duration">{{ __('app.duration_30min') }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <i data-lucide="star" class="w-4 h-4 text-quiz-warning fill-current"></i>
                            <span id="quiz-rating">4.5</span>
                        </div>
                    </div>

                    <!-- Mode Selector -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-foreground mb-3">{{ __('app.choose_mode') }}</h3>
                        <div class="grid grid-cols-1 gap-2">
                            <button
                                data-mode="all"
                                class="mode-button p-3 rounded-lg border-2 text-left transition-all border-primary bg-primary/5 text-foreground"
                            >
                                <div class="font-medium">{{ __('app.all_questions_mode') }}</div>
                                <div class="text-sm opacity-75">{{ __('app.complete_quiz_with_all_questions') }}</div>
                            </button>
                            {{--                            <button--}}
                            {{--                                data-mode="timed"--}}
                            {{--                                class="mode-button p-3 rounded-lg border-2 text-left transition-all border-border bg-secondary/30 text-muted-foreground hover:border-primary/50"--}}
                            {{--                            >--}}
                            {{--                                <div class="font-medium">Tryb czasowy</div>--}}
                            {{--                                <div class="text-sm opacity-75">Wyścig z czasem</div>--}}
                            {{--                            </button>--}}
                            {{--                            <button--}}
                            {{--                                data-mode="practice"--}}
                            {{--                                class="mode-button p-3 rounded-lg border-2 text-left transition-all border-border bg-secondary/30 text-muted-foreground hover:border-primary/50"--}}
                            {{--                            >--}}
                            {{--                                <div class="font-medium">Tryb ćwiczeń</div>--}}
                            {{--                                <div class="text-sm opacity-75">Bez limitu czasu, ucz się we własnym tempie</div>--}}
                            {{--                            </button>--}}
                        </div>
                    </div>

                    <!-- Selected Mode Info -->
                    <div class="bg-primary/5 border border-primary/20 rounded-lg p-4 mb-6">
                        <div class="flex items-center gap-2 text-sm">
                            <i data-lucide="book-open" class="w-4 h-4 text-primary"></i>
                            <span id="current-mode-questions" class="font-medium text-foreground">
                                @php
                                    $count = $topic->questions()->count();
                                    $questionText = $count == 1 ? __('app.question') :
                                        (($count % 10 >= 2 && $count % 10 <= 4 && ($count % 100 < 10 || $count % 100 >= 20)) ? __('app.questions_few') : __('app.questions_many'));
                                @endphp
                                {{ $count }} {{ $questionText }}
                            </span>
                            <span class="text-muted-foreground">•</span>
                            <span id="current-mode-desc" class="text-muted-foreground">
                                {{ __('app.complete_quiz_with_all_questions') }}
                            </span>
                        </div>
                    </div>

                    <!-- Start Button -->
                    <button
                        id="start-button"
                        wire:click="startSurvey"
                        class="w-full bg-gradient-primary hover:shadow-glow transition-all duration-300 text-lg text-white font-medium
           whitespace-nowrap h-11 rounded-md px-8 flex items-center justify-center"
                    >
                        <i data-lucide="play" class="w-5 h-5 mr-2 fill-current"></i>

                        <span class="block sm:hidden">{{ __('app.start') }}</span>
                        <span class="hidden sm:block">{{ __('app.start_all_questions') }}</span>
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>
