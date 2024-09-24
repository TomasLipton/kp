@php
    use Carbon\Carbon;
    Carbon::setLocale('pl');
@endphp

@script
<script>
    Livewire.hook('component.init', ({component, cleanup}) => {
        document.addEventListener('keydown', function (event) {
            console.log(document.getElementById('submit').length)
            if (event.key === 'Enter' && document.getElementById('wrap').dataset.isAnswered === 'true') {
                $wire.nextQuestion();
            }
            if (document.getElementById('wrap').dataset.questionType != 'single_text') {
                return
            }
            if (['1', '2', '3', '4'].includes(event.key)) {
                $wire.submitAnswerByOrder(event.key);
            }
            if (event.key === 'Enter') {
                $wire.nextQuestion();
            }
        });
    });
</script>
@endscript

<section
    @if($question)  data-question-type="{{$question->question_type}}" @endif
@if($question)  data-is-answered="{!! $chosenAnswer ? 'true' : 'false' !!}" @endif
    id="wrap">
    <div class="survey-question">
        <div wire:loading>
            Saving...
        </div>
        @if(!$quiz->completed_at)
            <div class="header">
                <div class="timer" x-data="{
    createdAt: new Date('{{$quiz->created_at}}'),
    timeElapsed: '00:00',
    updateSecondsElapsed() {
        const totalSeconds = Math.floor((new Date() - this.createdAt) / 1000);
        const minutes = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
        const seconds = String(totalSeconds % 60).padStart(2, '0');
        this.timeElapsed = `${minutes}:${seconds}`;
    }
}" x-init="setInterval(() => updateSecondsElapsed(), 1000)">
                    <div class="time">
                        {{$topic->name_pl}} |
                        <small>
                            @if($quiz->completed_at)
                                Zakończono o <b>{{ $quiz->updated_at->translatedFormat('F j, H:i')  }}</b>
                            @else
                                Czas:  <span x-text="timeElapsed"></span>
                            @endif</small>
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
                    @if(!$quiz->completed_at)
                        <button type="button" class="btn btn-secondary btn-sm" wire:click="finish">Skończyć</button>
                    @else
                        <button type="button" disabled class="btn btn-dander btn-sm">Test zakończony</button>
                    @endif

                </div>
            </div>



            <div class="question">
                {{$question->question_pl}}
            </div>
            <div class="answers" style="">
                @if($question->picture)
                    <div>
                        <img src="{{url('storage/' . $question->picture)}}" style=" max-width: 300px; border-radius: 8px;" alt="">
                    </div>
                @endif
                <div style=" width: 100%; ">
                    @if($question->question_type === 'single_text')
                        <ol>
                            @foreach($question->answers as $answer)
                                <li wire:key="{{ $answer->id }}" wire:click.debounce="submitAnswer('{{$answer->id}}')" @if($chosenAnswer && $answer->id == $chosenAnswer->id) style="text-decoration: underline" @endif >  {{$answer->text}}</li>
                            @endforeach
                        </ol>
                    @endif

                    @if($question->question_type === 'year')
                        <div class="answer-year-container" x-data="{ inputValue: '' }">
                            <input autofocus class="year-answer" x-model="inputValue"
                                   wire:keydown.enter="submitYear($event.target.value)"
                                   x-on:input="inputValue = inputValue.slice(0, 4).replace(/\D/g, '')"
                                   type="text"
                                   pattern="[0-9]*"
                                   inputmode="numeric"
                                   @if($chosenAnswer) readonly @endif
                                   maxlength="4"/>
                            <button class="submit-button" @click="$wire.submitYear(inputValue)">
                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="50" height="100" viewBox="0 0 40 40">
                                    <path fill="#bae0bd" d="M1.707 22.199L4.486 19.42 13.362 28.297 35.514 6.145 38.293 8.924 13.362 33.855z"></path>
                                    <path fill="#5e9c76" d="M35.514,6.852l2.072,2.072L13.363,33.148L2.414,22.199l2.072-2.072l8.169,8.169l0.707,0.707 l0.707-0.707L35.514,6.852 M35.514,5.438L13.363,27.59l-8.876-8.876L1,22.199l12.363,12.363L39,8.924L35.514,5.438L35.514,5.438z"></path>
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
            <div class="submit" id="submit" wire:click="nextQuestion" style="@if($chosenAnswer && $chosenAnswer->is_correct) background: #00d89e; @elseif($chosenAnswer && !$chosenAnswer->is_correct) background: #eb8989; @endif  @if($chosenAnswer) cursor:pointer @endif">
                @if($chosenAnswer)
                    <div class="enter">Kontynuować <span class="d-none d-sm-inline">[Enter]</span></div>
                @endif
                <p style="@if($chosenAnswer) margin-top: -25px @endif">Odpowiedź: @if(!$chosenAnswer)
                        __
                    @else
                        {{$chosenAnswer->order}}
                    @endif</p>
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
