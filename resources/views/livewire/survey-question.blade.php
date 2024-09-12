<div>
    <h1>{{$topic->name_pl}}</h1>

    {{$title}}<br>

    <input type="text" id="title" wire:model.live="title">

    <button wire:click="testClick">test</button>
</div>
