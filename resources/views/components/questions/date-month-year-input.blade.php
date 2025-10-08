@assets
    <style>
        .answer-date_month_year-container {
            justify-content: center;
            display: flex;
            /*padding-top: 50px;*/
            margin-bottom: 25px;
            outline: none;
            border-color: unset;

            input,
            select {
                outline: none;
                font-size: 25px;
                font-family: inherit;
                line-height: 1.2;
                width: 140px;
                &:focus {
                    outline: none;
                }
            }

            input {
                height: 70px;
                text-align: center;
                @media screen and (max-width: 550px) {
                    width: 25%;
                    height: 40px;
                }
            }

            input:first-child {
                border-radius: 8px 0 0 8px;
            }

            select {
                border-radius: 0;
                height: 70px;
                text-align: center;
                border-left: unset;
                @media screen and (max-width: 550px) {
                    height: 40px;
                }
            }


            @media screen and (max-width: 550px) {
                .submit-button {
                    height: 40px;
                }
            }
        }
    </style>
@endassets

<div class="answer-date_month_year-container" x-data="{ date: '', month: '1', year: '' }">
    <input autofocus class="day-answer" x-model="date"
           @keydown.enter="$wire.submitDateMonthYear(date, month, year)"
           x-on:input="date = date.slice(0, 2).replace(/\D/g, '')"
           x-on:clear-input.window="date = ''; month = '1'; year = ''"
           type="text"
           placeholder="Dzień"
           pattern="[0-9]*"
           inputmode="numeric"
           @if($chosenAnswer) readonly @endif
           maxlength="2"/>
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
    <input class="year-answer" x-model="year"
           @keydown.enter="$wire.submitDateMonthYear(date, month, year)"
           x-on:input="year = year.slice(0, 4).replace(/\D/g, '')"
           type="text"
           placeholder="Rok"
           pattern="[0-9]*"
           inputmode="numeric"
           @if($chosenAnswer) readonly @endif
           maxlength="4"/>

    <button class="
    submit-button

    bg-gradient-primary hover:shadow-glow transition-all duration-300 text-lg text-white font-medium
    " @click="$wire.submitDateMonthYear(date, month, year)">
        @svg('lucide-check', 'w-10 h-10')
    </button>
</div>
