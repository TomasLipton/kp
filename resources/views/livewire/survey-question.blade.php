@script
<script>
    Livewire.hook('component.init', ({component, cleanup}) => {
        console.log('component.init', component, cleanup);
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' && document.getElementById('wrap').dataset.isAnswered === 'true') {
                $wire.nextQuestion();
            }
            if (document.getElementById('wrap').dataset.questionType != 'single_text') {
                return
            }
            if (['1', '2', '3', '4'].includes(event.key)) {
                const answerElement = document.querySelector(`li[data-key="${event.key}"]`);
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

    @if($question)  data-question-type="{{$question->question_type}}" @endif
@if($question)  data-is-answered="{!! $chosenAnswer ? 'true' : 'false' !!}" @endif
    id="wrap">

    @if(!$quiz->completed_at)

        <div>
            <div class="m-2 flex justify-end items-center">
                <a
                    @click="if(confirm('Czy na pewno chcesz zakończyć quiz? Nie będziesz mógł wrócić do pytań.')) { $wire.finish() }"
                    class="inline-flex items-center justify-center gap-1
                    cursor-pointer
                    hover:bg-primary/10
                whitespace-nowrap rounded-md text-sm font-medium ring-offset-background
                transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-ring
                focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50
                bg-accent text-accent-foreground h-10 px-2 py-2"
                >
                    <i data-lucide="x " class="w-5 h-4"></i>
                    {{ __('Skończyć') }}
                </a>
            </div>
            @endif

            <div class="survey-question mt-14_ overflow-hidden bg-ca_rd @if(!$quiz->completed_at) shadow-card border @endif  border-border/50 rounded-lg pb-32" @if(!$quiz->completed_at) style="background-color: rgb(235, 235, 235);" @endif>

                @if(!$quiz->completed_at)
                    <div class="header">
                        <div class="timer">
                            <div>
                                {{$topic->name_pl}}
                            </div>
                        </div>
                        <div class="questions">
                            <span style="color: #00bb00" data-tooltip="Prawidłowe odpowiedzi"> {{$quiz->answers->filter(function ($answer) { return $answer->questionAnswer->is_correct; })->count()}}</span>
                            /
                            <span data-tooltip="Błędne odpowiedzi" style="color: #dd4444"> {{$quiz->answers->filter(function ($answer) { return !$answer->questionAnswer->is_correct; })->count()}}</span>
                            /
                            {{$quiz->answers->count()}}
                        </div>
                        <div class="actions">
                            <small class="flex gap-1 justify-end items-center" x-data="{
    createdAt: new Date('{{$quiz->created_at}}'),
    timeElapsed: '00:00',
    updateSecondsElapsed() {
        const totalSeconds = Math.floor((new Date() - this.createdAt) / 1000);
        const minutes = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
        const seconds = String(totalSeconds % 60).padStart(2, '0');
        this.timeElapsed = `${minutes}:${seconds}`;
    }
}" x-init="setInterval(() => updateSecondsElapsed(), 1000)">
                                <i data-lucide="timer " class="w-4"></i>
                                <span x-text="timeElapsed"></span>
                            </small>
                        </div>
                    </div>

                    <div class="question">

                            <div class="mb-4 p-3 bg-muted/50 rounded-lg border max-w-3xl m-auto">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2 text-sm text-muted-foreground">
                                        @svg('lucide-keyboard', 'w-4 h-4')
                                        <span>
        Use keyboard: Press <kbd class="px-2 py-1 text-xs bg-background border rounded">1-{{ count($questionAnswers) }}</kbd> to select answers,
        <kbd class="px-2 py-1 text-xs bg-background border rounded">Enter</kbd> to continue
      </span>
                                    </div>
                                    <button type="button" class="text-muted-foreground hover:text-foreground transition-colors" onclick="this.closest('div[class*=mb-4]').style.display='none'">
                                        @svg('lucide-x', 'w-4 h-4')
                                    </button>
                                </div>
                            </div>


                        <div class="question_container">
                            <div style="max-width: 88%"> {{$question->question_pl}}</div>
                            @if($question->aiSpeach()->count() > 0)
                                <div style="width: 10%">
                                    <img class="playAudio" id="playAudio" src="/assets/img.png" alt=""/>
                                    <audio id="audioPlayer" src="{{Storage::temporaryUrl( $question->aiSpeach->last()->path_to_audio, now()->addMinutes(10) )}}"></audio>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="answers" style="">
                        @if($question->picture)
                            <div style="margin: 0 auto;">
                                <img src="{{url('storage/' . $question->picture)}}" style=" max-width: 300px; border-radius: 8px;" alt="">
                            </div>
                        @endif
                        <div style=" width: 100%; ">
                            @if($question->question_type === 'single_text')
                                <ol>
                                    @foreach($questionAnswers as $answer)
                                        <li wire:key="{{ $answer->id }}" data-answer-id="{{$answer->id}}" data-key="{{$loop->index + 1}}" wire:click.debounce="submitAnswer('{{$answer->id}}')"
                                            @style([
                'color: #00bb00' => $answer->is_correct && $chosenAnswer,
                'color: #dd4444' => $answer->id === $chosenAnswer?->id && !$answer->is_correct,
                'text-decoration: underline; font-weight: bold;' => $chosenAnswer && $answer->id == $chosenAnswer->id
            ])
                                        > {{$answer->text}}</li>
                                    @endforeach
                                </ol>
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
                        </div>
                    </div>
                    <div class="submit" id="submit" wire:click="nextQuestion" style="@if($chosenAnswer && $chosenAnswer->is_correct) background: #00d89e; @elseif($chosenAnswer && !$chosenAnswer->is_correct) background: #eb8989; @endif  @if($chosenAnswer) cursor:pointer @endif">
                        @if($chosenAnswer)
                            <div class="enter">Kontynuować <span class="d-none d-sm-inline">[Enter]</span></div>
                        @endif
                        <p style="@if($chosenAnswer) margin-top: -25px @endif">
                            @if($question->question_type === 'single_text' || !$chosenAnswer)
                                Odpowiedź:
                            @endif

                            @if(!$chosenAnswer)
                                __
                            @elseif($question->question_type === 'single_text')
                                {{$chosenAnswer->order}}
                            @endif
                        </p>
                        <p style="color: white; font-size: 30px; margin-top: 10px">@if($chosenAnswer && $chosenAnswer->is_correct)
                                Prawidłowa odpowiedź
                            @elseif($chosenAnswer && !$chosenAnswer->is_correct)
                                Zła odpowiedź
                            @endif</p>
                    </div>

                @else
                    <livewire:survey-results :quiz="$quiz"/>
                @endif
            </div>

</section>
