@php
    use Carbon\Carbon;
    Carbon::setLocale('pl');
@endphp

<div class="survey-question">
    <div class="header">
        <div x-data="{
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
                @if($quiz->is_completed)
                    Zakończono o <b>{{ $quiz->updated_at->translatedFormat('F j, H:i')  }}</b>
                @else
                    Czas:  <span x-text="timeElapsed"></span>
                @endif
            </div>
        </div>
        <div class="questions">0</div>
        <div class="actions">
            @if(!$quiz->is_completed)
                <button type="button" class="btn btn-secondary btn-sm" wire:click="finish">Skończyć</button>
            @else
                <button type="button"  disabled class="btn btn-dander btn-sm" >Test zakończony</button>
            @endif

        </div>
    </div>
    <h1>{{$topic->name_pl}}</h1>

    {{$quiz->type}}<br>
    {{$title}}<br>

    <input type="text" id="title" wire:model.live="title">

    <button wire:click="testClick">test</button>
</div>
