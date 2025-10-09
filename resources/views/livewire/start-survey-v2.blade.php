@assets
@vite('resources/css/start-survey.scss')
<style>
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-bounce-slow {
        animation: bounce-slow 3s ease-in-out infinite;
    }
</style>

@endassets

<div class="min-h-screen py-8 px-4 relative overflow-hidden">
    <!-- Decorative Background Elements -->
    <div class="absolute inset-0 -z-10 overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-primary/20 to-transparent rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-tr from-quiz-warning/20 to-transparent rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    <!-- Back Button -->
    <div class="max-w-5xl mx-auto mb-6">
        <a
            href="{{ route('dashboard') }}"
            wire:navigate
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium
                   bg-card hover:bg-accent border border-border/50 transition-all duration-300
                   hover:scale-105 hover:shadow-lg group"
        >
            @svg('lucide-arrow-left', 'w-4 h-4 transition-transform group-hover:-translate-x-1')
            {{ __('app.back_to_topics') }}
        </a>
    </div>

    <!-- Main Card -->
    <div class="max-w-5xl mx-auto">
        <div class="relative overflow-hidden bg-gradient-card shadow-[var(--shadow-card)] border border-border/50 rounded-3xl backdrop-blur-sm transition-all duration-500 hover:shadow-2xl hover:scale-[1.01]">

            <!-- Hero Image Section -->
            <div class="relative h-64 md:h-80 overflow-hidden group">
                <img
                    id="quiz-image"
                    src="{{url('storage/' . $topic->picture)}}"
                    alt="{{ __('app.quiz_title_alt') }}"
                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                />
                <!-- Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>

                <!-- Difficulty Badge -->
                <div class="absolute top-6 right-6 animate-bounce-slow">
                    <span
                        id="difficulty-badge"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-semibold
                               bg-gradient-to-r from-orange-500 to-red-500 text-white shadow-lg backdrop-blur-md
                               border border-white/20"
                    >
                        @svg('lucide-flame', 'w-4 h-4')
                        {{ __('app.difficulty_medium') }}
                    </span>
                </div>

                <!-- Title Overlay (on image) -->
                <div class="absolute bottom-6 left-6 right-6">
                    <h1 id="quiz-title" class="text-3xl md:text-4xl lg:text-5xl font-bold text-white drop-shadow-2xl leading-tight">
                        @switch(LaravelLocalization::getCurrentLocale())
                            @case('ru')
                                {{trim($topic->name_ru ?? $topic->name_pl)}}
                                @break
                            @case('uk')
                                {{trim($topic->name_uk ?? $topic->name_pl)}}
                                @break
                            @case('be')
                                {{trim($topic->name_by ?? $topic->name_pl)}}
                                @break
                            @default
                                {{trim($topic->name_pl)}}
                        @endswitch
                    </h1>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-6 md:p-8 lg:p-10">

                <!-- Description with fancy border -->
                <div class="relative mb-8">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-primary rounded-full"></div>
                    <p id="quiz-description" class="text-base md:text-lg text-muted-foreground leading-relaxed pl-6">
                        @switch(LaravelLocalization::getCurrentLocale())
                            @case('ru')
                                {{trim($topic->description_ru ?? $topic->description_pl)}}
                                @break
                            @case('uk')
                                {{trim($topic->description_uk ?? $topic->description_pl)}}
                                @break
                            @case('be')
                                {{trim($topic->description_by ?? $topic->description_pl)}}
                                @break
                            @default
                                {{trim($topic->description_pl)}}
                        @endswitch
                    </p>
                </div>

                <!-- Stats Row with Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                    <div class="flex items-center gap-3 p-4 rounded-2xl bg-gradient-to-br from-primary/10 to-primary/5 border border-primary/20 transition-all duration-300 hover:scale-105 hover:shadow-lg group">
                        <div class="p-2 rounded-xl bg-primary/20 group-hover:bg-primary/30 transition-colors">
                            @svg('lucide-book-open', 'w-5 h-5 text-primary')
                        </div>
                        <div>
                            <div class="text-xs text-muted-foreground font-medium">{{ __('app.questions') }}</div>
                            <div id="quiz-questions" class="text-lg font-bold text-foreground">
                                @php
                                    $count = $topic->questions()->count();
                                @endphp
                                {{ $count }}
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-4 rounded-2xl bg-gradient-to-br from-quiz-info/10 to-quiz-info/5 border border-purple-200 transition-all duration-300 hover:scale-105 hover:shadow-lg group">
                        <div class="p-2 rounded-xl bg-purple-100 group-hover:bg-purple-200 transition-colors">
                            @svg('lucide-clock', 'w-5 h-5 text-purple-600')
                        </div>
                        <div>
                            <div class="text-xs text-muted-foreground font-medium">{{ __('app.duration') }}</div>
                            <div id="quiz-duration" class="text-lg font-bold text-foreground">30 {{ __('app.min') }}</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-4 rounded-2xl bg-gradient-to-br from-amber-100/50 to-amber-50 border border-amber-200 transition-all duration-300 hover:scale-105 hover:shadow-lg group">
                        <div class="p-2 rounded-xl bg-amber-100 group-hover:bg-amber-200 transition-colors">
                            @svg('lucide-star', 'w-5 h-5 text-amber-500 fill-amber-500')
                        </div>
                        <div>
                            <div class="text-xs text-muted-foreground font-medium">{{ __('app.rating') }}</div>
                            <div id="quiz-rating" class="text-lg font-bold text-foreground">4.5</div>
                        </div>
                    </div>
                </div>

                <!-- Mode Selector with Animation -->
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="h-px flex-1 bg-gradient-to-r from-transparent via-border to-transparent"></div>
                        <h3 class="text-sm font-semibold text-foreground flex items-center gap-2">
                            @svg('lucide-settings', 'w-4 h-4 text-primary')
                            {{ __('app.choose_mode') }}
                        </h3>
                        <div class="h-px flex-1 bg-gradient-to-r from-transparent via-border to-transparent"></div>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        <button
                            data-mode="all"
                            class="mode-button group p-5 rounded-2xl border-2 text-left transition-all duration-300
                                   border-primary bg-gradient-to-br from-primary/10 to-primary/5 text-foreground
                                   hover:shadow-[var(--shadow-glow)] hover:scale-[1.02] relative overflow-hidden"
                        >
                            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-primary/20 to-transparent rounded-full blur-2xl -translate-y-16 translate-x-16 group-hover:translate-y-0 group-hover:translate-x-0 transition-transform duration-500"></div>
                            <div class="relative flex items-center gap-4">
                                <div class="p-3 rounded-xl bg-primary/20 group-hover:bg-primary/30 transition-colors">
                                    @svg('lucide-list-checks', 'w-6 h-6 text-primary')
                                </div>
                                <div class="flex-1">
                                    <div class="font-bold text-lg mb-1">{{ __('app.all_questions_mode') }}</div>
                                    <div class="text-sm text-muted-foreground">{{ __('app.complete_quiz_with_all_questions') }}</div>
                                </div>
                                <div class="ml-auto">
                                    @svg('lucide-check-circle', 'w-6 h-6 text-primary')
                                </div>
                            </div>
                        </button>
                        <button
                            data-mode="all"
                            class="mode-button group p-5 rounded-2xl border-2 text-left transition-all duration-300
                                   border-primary bg-gradient-to-br from-primary/10 to-primary/5 text-foreground
                                   hover:shadow-[var(--shadow-glow)] hover:scale-[1.02] relative overflow-hidden"
                        >
                            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-primary/20 to-transparent rounded-full blur-2xl -translate-y-16 translate-x-16 group-hover:translate-y-0 group-hover:translate-x-0 transition-transform duration-500"></div>
                            <div class="relative flex items-center gap-4">
                                <div class="p-3 rounded-xl bg-primary/20 group-hover:bg-primary/30 transition-colors">
                                    @svg('lucide-list-checks', 'w-6 h-6 text-primary')
                                </div>
                                <div class="flex-1">
                                    <div class="font-bold text-lg mb-1">{{ __('app.all_questions_mode') }}</div>
                                    <div class="text-sm text-muted-foreground">{{ __('app.complete_quiz_with_all_questions') }}</div>
                                </div>
                                <div class="ml-auto">
                                    @svg('lucide-check-circle', 'w-6 h-6 text-primary')
                                </div>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Selected Mode Info with Glass Effect -->
                <div class="relative overflow-hidden rounded-2xl p-5 mb-8 bg-gradient-to-br from-primary/5 via-transparent to-quiz-info/5 border border-primary/20 backdrop-blur-sm">
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0zNiAxOGMtMy4zMTQgMC02IDIuNjg2LTYgNnMyLjY4NiA2IDYgNiA2LTIuNjg2IDYtNi0yLjY4Ni02LTYtNnoiIHN0cm9rZT0iY3VycmVudENvbG9yIiBzdHJva2Utb3BhY2l0eT0iLjA1IiBzdHJva2Utd2lkdGg9IjIiLz48L2c+PC9zdmc+')] opacity-50"></div>
                    <div class="relative flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-primary/20">
                            @svg('lucide-info', 'w-5 h-5 text-primary')
                        </div>
                        <div class="flex-1 flex flex-wrap items-center gap-2 text-sm">
                            <span id="current-mode-questions" class="font-semibold text-foreground">
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
                </div>

                <!-- Start Button with Enhanced Animation -->
                <button
                    id="start-button"
                    wire:click="startSurvey"
                    class="group relative w-full bg-gradient-primary hover:shadow-[var(--shadow-glow)]
                           transition-all duration-500 text-lg text-white font-bold
                           h-14 rounded-2xl px-8 flex items-center justify-center
                           hover:scale-[1.02] active:scale-[0.98] overflow-hidden"
                >
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                    <span class="relative flex items-center gap-3">
                        <span class="p-1.5 rounded-lg bg-white/20 group-hover:bg-white/30 transition-colors">
                            @svg('lucide-play', 'w-5 h-5 fill-current')
                        </span>
                        <span class="block sm:hidden">{{ __('app.start') }}</span>
                        <span class="hidden sm:block">{{ __('app.start_all_questions') }}</span>
                        @svg('lucide-arrow-right', 'w-5 h-5 transition-transform group-hover:translate-x-1')
                    </span>
                </button>

            </div>
        </div>
    </div>
</div>

