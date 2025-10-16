@script
<script>
    Livewire.hook('component.init', ({component, cleanup}) => {
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' && document.getElementById('wrap').dataset.isAnswered === 'true') {
                $wire.nextQuestion();
            }
            if (document.getElementById('wrap').dataset.questionType != 'single_text') {
                return
            }
            if (['1', '2', '3', '4'].includes(event.key)) {
                const answerElement = document.querySelector(`button[data-key="${event.key}"]`);
                if (answerElement) {
                    const answerId = answerElement.getAttribute('data-answer-id');
                    $wire.submitAnswer(answerId);
                }
            }
            if (event.key === 'Enter') {
                $wire.nextQuestion();
            }
        });
        if (document.getElementById('playAudio')) {
            document.getElementById('playAudio').addEventListener('click', function () {
                var audio = document.getElementById('audioPlayer');
                audio.play();
            });
        }
    });
</script>
@endscript

<section
    @if($question) data-question-type="{{$question->question_type}}" @endif
    @if($question) data-is-answered="{!! $chosenAnswer ? 'true' : 'false' !!}" @endif
    id="wrap"
    class="py-4"
>
    @if(!$quiz->completed_at)
        {{-- Header with Quit Button --}}
        <div class=" mx-auto mb-4">
            <div class="flex justify-end items-center">
                <button
                    @click="if(confirm('Czy na pewno chcesz zakończyć quiz? Nie będziesz mógł wrócić do pytań.')) { $wire.finish() }"
                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium
                           bg-red-50 hover:bg-red-100 border border-red-200 hover:border-red-300
                           text-red-700 transition-all duration-200"
                >
                    @svg('lucide-x', 'w-4 h-4')
                    {{ __('Skończyć') }}
                </button>
            </div>
        </div>

        {{-- Main Quiz Container --}}
        <div class=" mx-auto">
            <div class="overflow-hidden bg-gradient-card backdrop-blur-sm shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)]
                        border border-white/40 rounded-2xl relative
                        before:absolute before:inset-0 before:rounded-2xl before:p-[1px]
                        before:bg-gradient-to-br before:from-white/50 before:via-white/20 before:to-transparent before:-z-10">

                {{-- Stats Header --}}
                <div class="bg-gradient-to-r from-primary/5 via-secondary/5 to-primary/5 border-b border-border/30 p-4">
                    <div class="grid grid-cols-3 gap-4 max-w-4xl_ mx-auto">
                        {{-- Topic Name --}}
                        <div class="flex items-center gap-2">
                            @svg('lucide-book-open', 'w-5 h-5 text-primary flex-shrink-0')
                            <span class="text-sm font-medium text-foreground truncate">{{$topic->name_pl}}</span>
                        </div>

                        {{-- Score Stats --}}
                        <div class="flex items-center justify-center gap-2">
                            <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-50 border border-green-200">
                                @svg('lucide-check-circle', 'w-4 h-4 text-green-600')
                                <span class="text-sm font-semibold text-green-700">
                                    {{$quiz->answers->filter(function ($answer) { return $answer->questionAnswer->is_correct; })->count()}}
                                </span>
                            </div>
                            <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 border border-red-200">
                                @svg('lucide-x-circle', 'w-4 h-4 text-red-600')
                                <span class="text-sm font-semibold text-red-700">
                                    {{$quiz->answers->filter(function ($answer) { return !$answer->questionAnswer->is_correct; })->count()}}
                                </span>
                            </div>
                            @if($quiz->type === '10_questions')
                                <span class="text-sm text-muted-foreground">/ {{$quiz->questions_amount}}</span>
                            @endif
                        </div>

                        {{-- Timer --}}
                        <div class="flex items-center justify-end gap-2" x-data="{
                            createdAt: new Date('{{$quiz->created_at}}'),
                            timeElapsed: '00:00',
                            updateSecondsElapsed() {
                                const totalSeconds = Math.floor((new Date() - this.createdAt) / 1000);
                                const minutes = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
                                const seconds = String(totalSeconds % 60).padStart(2, '0');
                                this.timeElapsed = `${minutes}:${seconds}`;
                            }
                        }" x-init="setInterval(() => updateSecondsElapsed(), 1000)">
                            @svg('lucide-timer', 'w-5 h-5 text-primary')
                            <span class="text-sm font-medium text-foreground" x-text="timeElapsed"></span>
                        </div>
                    </div>
                </div>

                {{-- Question Section --}}
                <div class="p-6 sm:p-8">
                    <div class="max-w-4xl_ mx-auto">
                        {{-- Question Text --}}
                        <div class="mb-8">
                            <div class="flex items-start gap-4">
                                <div class="flex-1">
                                    <h2 class="text-xl sm:text-2xl font-semibold text-foreground leading-relaxed">
                                        {{$question->question_pl}}
                                    </h2>
                                </div>
                                @if($question->aiSpeach()->count() > 0)
                                    <button
                                        id="playAudio"
                                        class="flex-shrink-0 w-12 h-12 rounded-full bg-primary/10 hover:bg-primary/20
                                               border-2 border-primary/30 hover:border-primary/50
                                               flex items-center justify-center transition-all duration-200 hover:scale-110"
                                    >
                                        @svg('lucide-volume-2', 'w-5 h-5 text-primary')
                                    </button>
                                    <audio id="audioPlayer" src="{{Storage::temporaryUrl($question->aiSpeach->last()->path_to_audio, now()->addMinutes(10))}}"></audio>
                                @endif
                            </div>
                        </div>

                        {{-- Question Image --}}
                        @if($question->picture)
                            <div class="mb-8 flex justify-center">
                                <img
                                    src="{{url('storage/' . $question->picture)}}"
                                    alt="Question illustration"
                                    class="max-w-full sm:max-w-md rounded-xl shadow-lg border border-border/30"
                                >
                            </div>
                        @endif

                        {{-- Answers Section --}}
                        <div class="space-y-3">
                            @if($question->question_type === 'single_text')
                                @if($showKeyboardHelp)
                                    <x-keyboard-help :answersCount="count($questionAnswers)" />
                                @endif

                                @foreach($questionAnswers as $index => $answer)
                                    <button
                                        wire:key="{{ $answer->id }}"
                                        data-answer-id="{{$answer->id}}"
                                        data-key="{{$loop->index + 1}}"
                                        wire:click.debounce="submitAnswer('{{$answer->id}}')"
                                        @class([
                                            'w-full text-left p-4 rounded-xl transition-all duration-200 border-2 group',
                                            'bg-green-50 border-green-400 hover:bg-green-100' => $answer->is_correct && $chosenAnswer,
                                            'bg-red-50 border-red-400 hover:bg-red-100' => $answer->id === $chosenAnswer?->id && !$answer->is_correct,
                                            'border-border/30 bg-white/50 hover:bg-white hover:border-primary/30 hover:shadow-md' => !$chosenAnswer || $answer->id !== $chosenAnswer?->id,
                                        ])
                                    >
                                        <div class="flex items-center gap-4">
                                            <span @class([
                                                'flex items-center justify-center w-8 h-8 rounded-lg text-sm font-bold transition-all',
                                                'bg-green-200 text-green-700' => $answer->is_correct && $chosenAnswer,
                                                'bg-red-200 text-red-700' => $answer->id === $chosenAnswer?->id && !$answer->is_correct,
                                                'bg-primary/10 text-primary border border-primary/20' => !$chosenAnswer || $answer->id !== $chosenAnswer?->id,
                                            ])>
                                                {{ $index + 1 }}
                                            </span>
                                            <span @class([
                                                'flex-1 text-base font-medium',
                                                'text-green-900' => $answer->is_correct && $chosenAnswer,
                                                'text-red-900' => $answer->id === $chosenAnswer?->id && !$answer->is_correct,
                                                'text-foreground' => !$chosenAnswer || $answer->id !== $chosenAnswer?->id,
                                            ])>
                                                {{$answer->text}}
                                            </span>
                                            @if($chosenAnswer && $answer->is_correct)
                                                @svg('lucide-check-circle', 'w-6 h-6 text-green-600 flex-shrink-0')
                                            @elseif($chosenAnswer && $answer->id === $chosenAnswer->id && !$answer->is_correct)
                                                @svg('lucide-x-circle', 'w-6 h-6 text-red-600 flex-shrink-0')
                                            @endif
                                        </div>
                                    </button>
                                @endforeach
                            @endif

                            @if($question->question_type === 'year')
                                <x-questions.numeric-input :question="$question" :chosenAnswer="$chosenAnswer" type="year" />
                            @endif

                            @if($question->question_type === 'number')
                                <x-questions.numeric-input :question="$question" :chosenAnswer="$chosenAnswer" type="number" />
                            @endif

                            @if($question->question_type === 'date_month')
                                <x-questions.date-month-input :chosenAnswer="$chosenAnswer"/>
                            @endif

                            @if($question->question_type === 'date_month_year')
                                <x-questions.date-month-year-input :chosenAnswer="$chosenAnswer"/>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Answer Feedback & Continue --}}
                <div @class([
                    'border-t transition-all duration-300',
                    'bg-gradient-to-r from-green-50 via-green-100 to-green-50 border-green-200' => $chosenAnswer && $chosenAnswer->is_correct,
                    'bg-gradient-to-r from-red-50 via-red-100 to-red-50 border-red-200' => $chosenAnswer && !$chosenAnswer->is_correct,
                    'bg-gray-50 border-border/30' => !$chosenAnswer,
                ])>
                    <div class="max-w-4xl mx-auto p-6">
                        @if($chosenAnswer)
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    @if($chosenAnswer->is_correct)
                                        <div class="w-12 h-12 rounded-full bg-green-500 flex items-center justify-center">
                                            @svg('lucide-check', 'w-6 h-6 text-white')
                                        </div>
                                        <div>
                                            <p class="text-lg font-semibold text-green-900">Prawidłowa odpowiedź!</p>
                                            <p class="text-sm text-green-700">Świetna robota, kontynuuj dalej</p>
                                        </div>
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-red-500 flex items-center justify-center">
                                            @svg('lucide-x', 'w-6 h-6 text-white')
                                        </div>
                                        <div>
                                            <p class="text-lg font-semibold text-red-900">Nieprawidłowa odpowiedź</p>
                                            <p class="text-sm text-red-700">Nie martw się, następnym razem pójdzie lepiej</p>
                                        </div>
                                    @endif
                                </div>

                                <button
                                    wire:click="nextQuestion"
                                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold
                                           bg-gradient-primary text-white shadow-glow
                                           hover:shadow-[0_15px_50px_-15px_hsl(var(--primary))]
                                           transition-all duration-300 hover:scale-105"
                                >
                                    <span>Kontynuować</span>
                                    <kbd class="hidden sm:inline-block px-2 py-1 text-xs bg-white/20 rounded border border-white/30">Enter</kbd>
                                    @svg('lucide-arrow-right', 'w-5 h-5')
                                </button>
                            </div>
                        @else
                            <div class="text-center">
                                <p class="text-sm text-muted-foreground">
                                    @if($question->question_type === 'single_text')
                                        Wybierz odpowiedź używając myszy lub klawiatury (1-4)
                                    @else
                                        Wprowadź swoją odpowiedź
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Quiz Results --}}
        <livewire:survey-results :quiz="$quiz"/>
    @endif
</section>
