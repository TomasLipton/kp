<div class="answer-date_month-container" x-data="{ date: '', month: '1' }">
    <input autofocus class="year-answer" x-model="date"
           wire:keydown.enter="submitYear($event.target.value)"
           x-on:input="date = date.slice(0, 2).replace(/\D/g, '')"
           x-on:clear-input.window="date = ''; month = '1'"
           type="text"
           placeholder="Dzień"
           pattern="[0-9]*"
           inputmode="numeric"
           @if($chosenAnswer) readonly @endif
           maxlength="4"/>
    <select x-model="month" @if($chosenAnswer) disabled @endif>
        <option value="1">Styczeń</option>
        <option value="2">Luty</option>
        <option value="3">Marzec</option>
        <option value="4">Kwiecień</option>
        <option value="5">Maj</option>
        <option value="6">Czerwiec</option>
        <option value="7">Lipiec</option>
        <option value="8">Sierpień</option>
        <option value="9">Wrzesień</option>
        <option value="10">Październik</option>
        <option value="11">Listopad</option>
        <option value="12">Grudzień</option>
    </select>

    <button class="
    submit-button

    bg-gradient-primary hover:shadow-glow transition-all duration-300 text-lg text-white font-medium
    " @click="$wire.submitDateMonth(date, month)">
        <i data-lucide="check" class="w-10 h-10"></i>
    </button>
</div>
