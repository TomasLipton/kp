@props(['question', 'chosenAnswer', 'type' => 'number'])

<div class="answer-year-container" x-data="{ inputValue{{$question->id}}: '' }">
    <input autofocus
           class="year-answer"
           x-model="inputValue{{$question->id}}"
           wire:keydown.enter="submit{{ ucfirst($type) }}($event.target.value)"
           x-on:input="inputValue{{$question->id}} = inputValue{{$question->id}}.slice(0, 4).replace(/\D/g, '')"
           type="text"
           placeholder="{{ $type === 'year' ? 'Rok' : '1234' }}"
           pattern="[0-9]*"
           inputmode="numeric"
           @if($chosenAnswer) readonly @endif
           maxlength="4"/>
    <button class="submit-button     bg-gradient-primary hover:shadow-glow transition-all duration-300 text-lg text-white font-medium" @click="$wire.submit{{ ucfirst($type) }}(inputValue{{$question->id}})">
        @svg('lucide-check', 'w-10 h-10')
    </button>
</div>
