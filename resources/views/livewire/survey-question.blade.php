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
                        <div style="max-width: 88%"> {{$question->question_pl}}</div>
                        @if($question->aiSpeach()->count() > 0)
                            <div style="width: 10%">
                                <img class="playAudio" id="playAudio" src="/assets/img.png" alt=""/>
                                <audio id="audioPlayer" src="{{Storage::temporaryUrl( $question->aiSpeach->last()->path_to_audio, now()->addMinutes(10) )}}"></audio>
                            </div>
                        @endif
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
                                <div class="answer-year-container" x-data="{ inputValue{{$question->id}}: '' }">
                                    <input autofocus class="year-answer" x-model="inputValue{{$question->id}}"
                                           wire:keydown.enter="submitYear($event.target.value)"
                                           x-on:input="inputValue{{$question->id}} = inputValue{{$question->id}}.slice(0, 4).replace(/\D/g, '')"
                                           type="text"
                                           pattern="[0-9]*"
                                           inputmode="numeric"
                                           placeholder="Rok"
                                           @if($chosenAnswer) readonly @endif
                                           maxlength="4"/>
                                    <button class="submit-button" @click="$wire.submitYear(inputValue{{$question->id}})">
                                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="50" height="100" viewBox="0 0 40 40">
                                            <path fill="#bae0bd" d="M1.707 22.199L4.486 19.42 13.362 28.297 35.514 6.145 38.293 8.924 13.362 33.855z"></path>
                                            <path fill="#5e9c76" d="M35.514,6.852l2.072,2.072L13.363,33.148L2.414,22.199l2.072-2.072l8.169,8.169l0.707,0.707 l0.707-0.707L35.514,6.852 M35.514,5.438L13.363,27.59l-8.876-8.876L1,22.199l12.363,12.363L39,8.924L35.514,5.438L35.514,5.438z"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endif

                            @if($question->question_type === 'number')
                                <div class="answer-year-container" x-data="{ inputValue{{$question->id}}: '' }">
                                    <input autofocus class="year-answer" x-model="inputValue{{$question->id}}"
                                           wire:keydown.enter="submitNumber($event.target.value)"
                                           x-on:input="inputValue{{$question->id}} = inputValue{{$question->id}}.slice(0, 4).replace(/\D/g, '')"
                                           type="text"
                                           pattern="[0-9]*"
                                           inputmode="numeric"
                                           @if($chosenAnswer) readonly @endif
                                           maxlength="4"/>
                                    <button class="submit-button" @click="$wire.submitNumber(inputValue{{$question->id}})">
                                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="50" height="100" viewBox="0 0 40 40">
                                            <path fill="#bae0bd" d="M1.707 22.199L4.486 19.42 13.362 28.297 35.514 6.145 38.293 8.924 13.362 33.855z"></path>
                                            <path fill="#5e9c76" d="M35.514,6.852l2.072,2.072L13.363,33.148L2.414,22.199l2.072-2.072l8.169,8.169l0.707,0.707 l0.707-0.707L35.514,6.852 M35.514,5.438L13.363,27.59l-8.876-8.876L1,22.199l12.363,12.363L39,8.924L35.514,5.438L35.514,5.438z"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endif

                            @if($question->question_type === 'date_month')
                                <x-date-month-input :chosenAnswer="$chosenAnswer"/>
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
