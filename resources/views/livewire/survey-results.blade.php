<section class="min-h-screen py-8">
    <div class="mx-auto max-w-3xl">
        {{-- Results Header Card --}}
        <div class="overflow-hidden bg-gradient-card backdrop-blur-sm shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)]
                    border border-white/40 rounded-2xl relative mb-6
                    before:absolute before:inset-0 before:rounded-2xl before:p-[1px]
                    before:bg-gradient-to-br before:from-white/50 before:via-white/20 before:to-transparent before:-z-10">

            {{-- Success Banner --}}
            <div class="bg-gradient-to-r from-green-50 via-green-100 to-green-50 border-b border-green-200 p-6 text-center">
                <div class="flex flex-col items-center gap-3">
                    <div class="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center shadow-lg">
                        @svg('lucide-trophy', 'w-8 h-8 text-white')
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-green-900">{{ __('app.survey_results') }}</h1>
                        <p class="text-sm text-green-700 mt-1">Quiz zakończony pomyślnie!</p>
                        @if($quiz->type)
                            <span class="inline-block mt-2 px-3 py-1 bg-green-200 text-green-800 text-xs font-semibold rounded-full">
                                {{ ucfirst($quiz->type) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Score Overview --}}
            <div class="p-6 bg-white">
                <div class="grid grid-cols-2 gap-4 mb-6">
                    {{-- Correct Answers --}}
                    <div class="bg-green-50 border-2 border-green-200 rounded-xl p-4 text-center">
                        <div class="flex items-center justify-center gap-2 mb-2">
                            @svg('lucide-check-circle', 'w-5 h-5 text-green-600')
                            <span class="text-sm font-medium text-green-700">Poprawne</span>
                        </div>
                        <div class="text-3xl font-bold text-green-900">
                            {{$quiz->answers->filter(function ($answer) { return $answer->questionAnswer->is_correct; })->count()}}
                        </div>
                    </div>

                    {{-- Incorrect Answers --}}
                    <div class="bg-red-50 border-2 border-red-200 rounded-xl p-4 text-center">
                        <div class="flex items-center justify-center gap-2 mb-2">
                            @svg('lucide-x-circle', 'w-5 h-5 text-red-600')
                            <span class="text-sm font-medium text-red-700">Błędne</span>
                        </div>
                        <div class="text-3xl font-bold text-red-900">
                            {{$quiz->answers->filter(function ($answer) { return !$answer->questionAnswer->is_correct; })->count()}}
                        </div>
                    </div>
                </div>

                {{-- Progress Bar --}}
                @php
                    $correctCount = $quiz->answers->filter(function ($answer) { return $answer->questionAnswer->is_correct; })->count();
                    $totalCount = $quiz->answers->count();
                    $percentage = $totalCount > 0 ? round(($correctCount / $totalCount) * 100) : 0;
                @endphp
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-foreground">Wynik końcowy</span>
                        <span class="text-sm font-bold text-primary">{{ $percentage }}%</span>
                    </div>
                    <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-green-500 to-green-600 rounded-full transition-all duration-500"
                             style="width: {{ $percentage }}%"></div>
                    </div>
                </div>

                {{-- Stats Grid --}}
                <div class="space-y-3">
                    {{-- Total Questions --}}
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center gap-3">
                            @svg('lucide-list-checks', 'w-5 h-5 text-primary')
                            <span class="font-medium text-foreground">{{ __('app.total_questions') }}</span>
                        </div>
                        <span class="text-lg font-bold text-foreground">{{$totalCount}}</span>
                    </div>

                    {{-- Time Taken --}}
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center gap-3">
                            @svg('lucide-timer', 'w-5 h-5 text-primary')
                            <span class="font-medium text-foreground">{{ __('app.time_taken') }}</span>
                        </div>
                        <span class="text-lg font-bold text-foreground">
                            @php
                                $diff = $quiz->created_at->diff($quiz->completed_at);
                                $minutes = $diff->i;
                                $seconds = $diff->s;
                            @endphp
                            {{ $minutes }}m {{ $seconds }}s
                        </span>
                    </div>

                    {{-- Date --}}
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center gap-3">
                            @svg('lucide-calendar', 'w-5 h-5 text-primary')
                            <span class="font-medium text-foreground">{{ __('app.date') }}</span>
                        </div>
                        <span class="text-lg font-bold text-foreground">{{$quiz->created_at->translatedFormat('F j, H:i') }}</span>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="p-6 bg-gray-50 border-t border-border/30">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                    <a
                        href="/topics"
                        wire:navigate
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold
                               bg-white hover:bg-gray-50 border-2 border-primary/30 hover:border-primary/50
                               text-primary transition-all duration-300 hover:scale-105"
                    >
                        @svg('lucide-list', 'w-5 h-5')
                        <span>{{ __('app.all_tests') }}</span>
                    </a>

                    <a
                        href="/{{$quiz->topics->slug}}"
                        wire:navigate
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold
                               bg-gradient-primary text-white shadow-glow
                               hover:shadow-[0_15px_50px_-15px_hsl(var(--primary))]
                               transition-all duration-300 hover:scale-105"
                    >
                        @svg('lucide-refresh-cw', 'w-5 h-5')
                        <span>{{ __('app.repeat_test') }}</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- Share Button --}}
                    <button
                        x-data="{
                            copied: false,
                            copyLink() {
                                navigator.clipboard.writeText(window.location.href);
                                this.copied = true;
                                setTimeout(() => this.copied = false, 2000);
                            }
                        }"
                        @click="copyLink()"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold
                               bg-blue-50 hover:bg-blue-100 border-2 border-blue-200 hover:border-blue-300
                               text-blue-700 transition-all duration-300"
                    >
                        <template x-if="!copied">
                            <div class="flex items-center gap-2">
                                @svg('lucide-share-2', 'w-5 h-5')
                                <span>Udostępnij wyniki</span>
                            </div>
                        </template>
                        <template x-if="copied">
                            <div class="flex items-center gap-2">
                                @svg('lucide-check', 'w-5 h-5')
                                <span>Skopiowano link!</span>
                            </div>
                        </template>
                    </button>

                    {{-- Analytics / Login Button --}}
                    @auth
                        <a
                            href="{{ route('profile') }}"
                            wire:navigate
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold
                                   bg-purple-50 hover:bg-purple-100 border-2 border-purple-200 hover:border-purple-300
                                   text-purple-700 transition-all duration-300"
                        >
                            @svg('lucide-bar-chart-3', 'w-5 h-5')
                            <span>Analityka</span>
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            wire:navigate
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold
                                   bg-purple-50 hover:bg-purple-100 border-2 border-purple-200 hover:border-purple-300
                                   text-purple-700 transition-all duration-300"
                        >
                            @svg('lucide-log-in', 'w-5 h-5')
                            <span>Zaloguj się</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Motivational Message --}}
        <div class="text-center p-6 bg-white/50 rounded-xl border border-border/30">
            @if($percentage >= 80)
                <p class="text-lg font-semibold text-green-700">🎉 Świetna robota! Doskonały wynik!</p>
            @elseif($percentage >= 60)
                <p class="text-lg font-semibold text-blue-700">👍 Dobra robota! Możesz być dumny!</p>
            @else
                <p class="text-lg font-semibold text-orange-700">💪 Nie poddawaj się! Następnym razem będzie lepiej!</p>
            @endif
        </div>
    </div>
</section>
