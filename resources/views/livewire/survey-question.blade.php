@php
    use Carbon\Carbon;
    Carbon::setLocale('pl');
@endphp

<section >
    <div class="survey-question">
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
               <span title="asd" data-tooltip="Błędne odpowiedzi" style="color: #dd4444"> {{$quiz->answers->filter(function ($answer) { return !$answer->questionAnswer->is_correct; })->count()}}</span>
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
            <div class="answers">
                <ol>
                    @foreach($question->answers as $answer)
                        <li wire:key="{{ $answer->id }}" wire:click="submitAnswer('{{$answer->id}}')">  {{$answer->text}}</li>
                    @endforeach
                </ol>

            </div>

            <div class="submit" wire:click="nextQuestion" style="@if($chosenAnswer && $chosenAnswer->is_correct) background: #00d89e; @elseif($chosenAnswer && !$chosenAnswer->is_correct) background: #eb8989; @endif  @if($chosenAnswer) cursor:pointer @endif">
                @if($chosenAnswer)
                    <div class="enter">Kontynuować [Enter]</div>
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
            <livewire:survey-results :quiz="$quiz" />
        @endif
    </div>


</section>
