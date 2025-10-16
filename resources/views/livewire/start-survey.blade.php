@assets
@vite('resources/css/start-survey.scss')
@endassets

<div class="min-h-screen ">
    <!-- Back Button -->
    <div class="m-2">
        <a
            href="{{ route('dashboard') }}"
            wire:navigate
            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium
                bg-secondary/50 hover:bg-secondary border border-border hover:border-primary/30
                text-foreground transition-all duration-200"
        >
            @svg('lucide-arrow-left', 'w-4 h-4')
            {{ __('app.back_to_topics') }}
        </a>
    </div>

    <!-- Main Content -->
    <div class="container_ max-w-[1200px]_ mx-auto">
        <div class="bg-card border border-border/50 rounded-2xl shadow-xl overflow-hidden
            transform transition-all duration-300 hover:shadow-2xl">

            <!-- Content Section -->
            <div class="p-6 sm:p-8 lg:p-10 space-y-8">
                <!-- Quiz Title -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between gap-4">
                        <h1 id="quiz-title" class="text-3xl sm:text-4xl lg:text-5xl font-bold text-foreground
                            bg-gradient-to-r from-foreground to-foreground/70 bg-clip-text
                            leading-tight tracking-tight">
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

                        <!-- Floating Badge -->
                        <span
                            id="difficulty-badge"
                            class="flex-shrink-0 inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full
                                text-xs font-semibold bg-orange-500 text-white
                                shadow-lg shadow-orange-500/30
                                ring-2 ring-orange-500/20"
                        >
                            @svg('lucide-zap', 'w-3.5 h-3.5')
                            {{ __('app.difficulty_medium') }}
                        </span>
                    </div>

                    <!-- Image and Description Section -->
                    <div>
                        <!-- Image floated left -->
                        <div class="float-left w-full sm:w-64 lg:w-80 mr-0 sm:mr-6 mb-4">
                            <div class="relative overflow-hidden rounded-xl group">
                                <img
                                    id="quiz-image"
                                    src="{{url('storage/' . $topic->picture)}}"
                                    alt="{{ __('app.quiz_title_alt') }}"
                                    class="w-full h-48 sm:h-56 object-cover transition-transform duration-700 group-hover:scale-105"
                                />
                                <!-- Gradient Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-background/60 via-transparent to-transparent"></div>
                            </div>
                        </div>

                        <!-- Description wraps around image -->
                        <p id="quiz-description" class="text-base sm:text-lg text-muted-foreground leading-relaxed">
                            @switch(LaravelLocalization::getCurrentLocale())
                                @case('ru')
                                    {!! str_replace("\n", '<br>', $topic->description_ru ?? $topic->description_pl) !!}
                                    @break
                                @case('uk')
                                    {!! str_replace("\n", '<br>', $topic->description_uk ?? $topic->description_pl) !!}
                                    @break
                                @case('be')
                                    {!! str_replace("\n", '<br>', $topic->description_by ?? $topic->description_pl) !!}
                                    @break
                                @default
                                    {!! str_replace("\n", '<br>', $topic->description_pl) !!}
                            @endswitch
                        </p>

                        <div class="clear-both"></div>
                    </div>
                </div>

                <!-- Stats Row with Cards -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                    <!-- Questions Count -->
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-br from-primary/5 to-primary/10
                        border border-primary/20 transition-all duration-300 hover:shadow-md hover:scale-105 group">
                        @svg('lucide-book-open', 'w-5 h-5 text-primary transition-transform group-hover:scale-110')
                        <div class="flex-1">
                            <span id="quiz-questions" class="text-lg font-bold text-foreground block">
                                @php
                                    $count = $topic->questions()->count();
                                    $questionText = $count == 1 ? __('app.question') :
                                        (($count % 10 >= 2 && $count % 10 <= 4 && ($count % 100 < 10 || $count % 100 >= 20)) ? __('app.questions_few') : __('app.questions_many'));
                                @endphp
                                {{ $count }}
                            </span>
                            <span class="text-xs text-muted-foreground">{{ $questionText }}</span>
                        </div>
                    </div>

                    <!-- User Attempts -->
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-br from-blue-500/5 to-blue-500/10
                        border border-blue-500/20 transition-all duration-300 hover:shadow-md hover:scale-105 group">
                        @svg('lucide-rotate-cw', 'w-5 h-5 text-blue-500 transition-transform group-hover:scale-110')
                        <div class="flex-1">
                            @php
                                $userAttempts = auth()->check() ? $topic->quizzes()->where('user_id', auth()->id())->count() : 0;
                            @endphp
                            <span class="text-lg font-bold text-foreground block">{{ $userAttempts }}</span>
                            <span class="text-xs text-muted-foreground">{{ __('app.your_attempts') ?? 'попыток' }}</span>
                        </div>
                    </div>

                    <!-- Completed Count -->
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-br from-green-500/5 to-green-500/10
                        border border-green-500/20 transition-all duration-300 hover:shadow-md hover:scale-105 group">
                        @svg('lucide-check-circle', 'w-5 h-5 text-green-500 transition-transform group-hover:scale-110')
                        <div class="flex-1">
                            @php
                                $completedCount = $topic->quizzes()->whereNotNull('completed_at')->count();
                            @endphp
                            <span class="text-lg font-bold text-foreground block">{{ $completedCount }}</span>
                            <span class="text-xs text-muted-foreground">{{ __('app.completed') ?? 'завершено' }}</span>
                        </div>
                    </div>

                    <!-- Total Attempts -->
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-br from-yellow-500/5 to-yellow-500/10
                        border border-yellow-500/20 transition-all duration-300 hover:shadow-md hover:scale-105 group">
                        @svg('lucide-users', 'w-5 h-5 text-yellow-500 transition-transform group-hover:scale-110')
                        <div class="flex-1">
                            @php
                                $totalAttempts = $topic->quizzes()->count();
                            @endphp
                            <span class="text-lg font-bold text-foreground block">{{ $totalAttempts }}</span>
                            <span class="text-xs text-muted-foreground">{{ __('app.total_attempts') ?? 'всего попыток' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Mode Selector -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        @svg('lucide-settings-2', 'w-5 h-5 text-primary')
                        <h3 class="text-lg font-bold text-foreground">{{ __('app.choose_mode') }}</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- 10 Questions Mode -->
                        <button
                            wire:click="setMode('10 pytań')"
                            class="group relative p-5 rounded-xl border-2 text-left
                                transition-all duration-300 transform hover:scale-[1.02]
                                {{ $surveyMode === '10 pytań'
                                    ? 'border-primary bg-gradient-to-br from-primary/10 to-primary/5 shadow-lg shadow-primary/20 ring-2 ring-primary/30'
                                    : 'border-border/50 bg-card hover:border-primary/50 hover:bg-primary/5 hover:shadow-md' }}"
                        >
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 p-2 rounded-lg {{ $surveyMode === '10 pytań' ? 'bg-primary/20' : 'bg-primary/10' }}
                                    transition-colors">
                                    @svg('lucide-zap', 'w-5 h-5 text-primary')
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-foreground mb-1 flex items-center gap-2">
                                        {{ __('app.10_questions_mode') }}
                                        @if($surveyMode === '10 pytań')
                                            @svg('lucide-check-circle-2', 'w-4 h-4 text-primary')
                                        @endif
                                    </div>
                                    <div class="text-sm text-muted-foreground">
                                        {{ __('app.test_yourself_with_10_random_questions') }}
                                    </div>
                                </div>
                            </div>
                        </button>

                        <!-- All Questions Mode -->
                        <button
                            wire:click="setMode('Wszystkie pytania')"
                            class="group relative p-5 rounded-xl border-2 text-left
                                transition-all duration-300 transform hover:scale-[1.02]
                                {{ $surveyMode === 'Wszystkie pytania'
                                    ? 'border-primary bg-gradient-to-br from-primary/10 to-primary/5 shadow-lg shadow-primary/20 ring-2 ring-primary/30'
                                    : 'border-border/50 bg-card hover:border-primary/50 hover:bg-primary/5 hover:shadow-md' }}"
                        >
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 p-2 rounded-lg {{ $surveyMode === 'Wszystkie pytania' ? 'bg-primary/20' : 'bg-primary/10' }}
                                    transition-colors">
                                    @svg('lucide-list-checks', 'w-5 h-5 text-primary')
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-foreground mb-1 flex items-center gap-2">
                                        {{ __('app.all_questions_mode') }}
                                        @if($surveyMode === 'Wszystkie pytania')
                                            @svg('lucide-check-circle-2', 'w-4 h-4 text-primary')
                                        @endif
                                    </div>
                                    <div class="text-sm text-muted-foreground">
                                        {{ __('app.complete_quiz_with_all_questions') }}
                                    </div>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Selected Mode Info -->
{{--                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary/10 via-primary/5 to-transparent--}}
{{--                    border border-primary/30 p-5 backdrop-blur-sm">--}}
{{--                    <!-- Decorative Background Elements -->--}}
{{--                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-3xl"></div>--}}
{{--                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-primary/5 rounded-full blur-2xl"></div>--}}

{{--                    <div class="relative flex items-center gap-3">--}}
{{--                        <div class="flex-shrink-0 p-2.5 rounded-lg bg-primary/20">--}}
{{--                            @svg('lucide-info', 'w-5 h-5 text-primary')--}}
{{--                        </div>--}}
{{--                        <div class="flex-1">--}}
{{--                            <div class="flex flex-wrap items-center gap-2 text-sm">--}}
{{--                                <span id="current-mode-questions" class="font-bold text-foreground text-base">--}}
{{--                                    @php--}}
{{--                                        if ($surveyMode === '10 pytań') {--}}
{{--                                            $count = 10;--}}
{{--                                        } else {--}}
{{--                                            $count = $topic->questions()->count();--}}
{{--                                        }--}}
{{--                                        $questionText = $count == 1 ? __('app.question') :--}}
{{--                                            (($count % 10 >= 2 && $count % 10 <= 4 && ($count % 100 < 10 || $count % 100 >= 20)) ? __('app.questions_few') : __('app.questions_many'));--}}
{{--                                    @endphp--}}
{{--                                    {{ $count }} {{ $questionText }}--}}
{{--                                </span>--}}
{{--                                <span class="text-primary/50 font-bold">•</span>--}}
{{--                                <span id="current-mode-desc" class="text-muted-foreground">--}}
{{--                                    {{ $surveyMode === '10 pytań' ? __('app.test_yourself_with_10_random_questions') : __('app.complete_quiz_with_all_questions') }}--}}
{{--                                </span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

                <!-- Start Button -->
                <button
                    id="start-button"
                    wire:click="startSurvey"
                    class="w-full inline-flex items-center justify-center gap-2 px-8 py-4 bg-gradient-primary text-white font-semibold text-lg rounded-xl
                        shadow-glow hover:shadow-[0_20px_60px_-15px_hsl(var(--primary))] transition-all duration-300 hover:scale-105"
                >
                    <span class="block sm:hidden">{{ __('app.start') }}</span>
                    <span class="hidden sm:block">
                        {{ $surveyMode === '10 pytań' ? __('app.start_10_questions') : __('app.start_all_questions') }}
                    </span>
                    @svg('lucide-arrow-right', 'w-5 h-5')
                </button>
            </div>
        </div>
    </div>
</div>
