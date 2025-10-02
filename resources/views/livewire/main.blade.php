@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
@endphp

@assets
@vite(['resources/css/main.scss'])

@endassets

<div>

    {{-- Hero Section --}}
    <div class="mt-8 mb-8 text-center">
        <h1 class="text-4xl md:text-6xl font-bold bg-gradient-primary bg-clip-text text-transparent mb-4">
            QuizPolaka
        </h1>
        <p class="text-xl md:text-2xl text-foreground/80 mb-6">
            Przygotuj się do egzaminu na Kartę Polaka
        </p>
        <p class="text-base md:text-lg text-muted-foreground max-w-2xl mx-auto mb-8">
            Sprawdź swoją wiedzę o polskiej historii, kulturze i języku. Rozwiązuj interaktywne testy i zwiększ swoje szanse na uzyskanie Karty Polaka.
        </p>

        {{-- Stats Section --}}
        <div class="flex flex-wrap justify-center gap-8 mb-8">
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-primary">{{$topics->count()}}</div>
                <div class="text-sm text-muted-foreground">Tematów</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-primary">{{$topics->sum(fn($t) => $t->questions()->count())}}</div>
                <div class="text-sm text-muted-foreground">Pytań</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-primary">100%</div>
                <div class="text-sm text-muted-foreground">Darmowe</div>
            </div>
        </div>

        {{-- CTA Buttons --}}
        <div class="flex flex-wrap justify-center gap-4 mb-8">
            <a href="{{ route('topics') }}" wire:navigate
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-primary text-white font-semibold rounded-lg
                      shadow-glow hover:shadow-[0_15px_50px_-15px_hsl(var(--primary))] transition-all duration-300 hover:scale-105">
                Przeglądaj tematy
                @svg('lucide-arrow-right', 'w-5 h-5')
            </a>
            @guest
                <a href="{{ route('register') }}" wire:navigate
                   class="inline-flex items-center gap-2 px-6 py-3 bg-white text-primary font-semibold rounded-lg border-2 border-primary
                          hover:bg-primary/5 transition-all duration-300 hover:scale-105">
                    Załóż konto za darmo
                    @svg('lucide-user-plus', 'w-5 h-5')
                </a>
            @endguest
        </div>
    </div>

    <div class="mt-14 overflow-hidden bg-gradient-card backdrop-blur-sm shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] border border-white/40 rounded-lg pb-8 relative
         before:absolute before:inset-0 before:rounded-lg before:p-[1px] before:bg-gradient-to-br before:from-white/50 before:via-white/20 before:to-transparent before:-z-10">
        <div class="text-center mb-8 mt-8">
            <h2 class="text-3xl md:text-4xl font-bold bg-gradient-primary bg-clip-text text-transparent mb-4">
                {{ __('Quiz Topics') }}
            </h2>
            <p class="text-lg text-muted-foreground">
                {{ __('Choose a topic to start your journey through Polish history and culture') }}
            </p>
        </div>

        <section class="hero-section ">
            <div class="card-grid">
                @foreach($topics->take(3) as $topic)
                    <a class="category-card rounded-lg" href="/{{$topic->slug}}" wire:navigate wire:navigate.hover>
                        <div class="card__background" style="background-image: url({{url('storage/' . $topic->picture)}})"></div>
                        <div class="card__content">
                            <p class="card__category">
                                {{$topic->questions()->count()}}
                                @php
                                    $count = $topic->questions()->count();
                                @endphp
                                @if($count === 1)
                                    {{ __('app.question') }}
                                @elseif($count % 10 >= 2 && $count % 10 <= 4 && ($count % 100 < 10 || $count % 100 >= 20))
                                    {{ __('app.questions_few') }}
                                @else
                                    {{ __('app.questions_many') }}
                                @endif
                            </p>
                            <h3 class="card__heading">
                                @switch(LaravelLocalization::getCurrentLocale())
                                    @case('ru')
                                        {{trim($topic->name_ru ?? $topic->name_pl)}}
                                        @break
                                    @case('uk')
                                        {{trim($topic->name_uk ?? $topic->name_pl)}}
                                        @break
                                    @case('be')
                                        {{trim($topic->name_be ?? $topic->name_pl)}}
                                        @break
                                    @default
                                        {{trim($topic->name_pl)}}
                                @endswitch
                            </h3>
                        </div>
                        <div class="absolute inset-0 border-2 scale-105 border-primary/0 hover:border-primary/50 transition-colors duration-300 rounded-lg"></div>
                    </a>
                @endforeach

                {{-- View All Topics Card --}}
                <a class="category-card rounded-lg" href="{{ route('topics') }}" wire:navigate wire:navigate.hover>
                    <div class="card__background" style="background: linear-gradient(135deg, hsl(var(--primary)) 0%, hsl(var(--primary) / 0.8) 100%)"></div>
                    <div class="card__content flex flex-col items-center justify-center h-full text-center">
                        <h3 class="card__heading text-2xl mb-2">
                            Zobacz wszystkie tematy
                        </h3>
                        <p class="card__category">
                            {{$topics->count()}} dostępnych tematów
                        </p>
                    </div>
                    <div class="absolute inset-0 border-2 scale-105 border-primary/0 hover:border-primary/50 transition-colors duration-300 rounded-lg"></div>
                </a>
            </div>
        </section>

    </div>

    {{-- How It Works Section --}}
    <div class="mt-16 mb-16">
        <h2 class="text-3xl md:text-4xl font-bold text-center bg-gradient-primary bg-clip-text text-transparent mb-12">
            Jak to działa?
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-primary/10 flex items-center justify-center">
                    <span class="text-3xl font-bold text-primary">1</span>
                </div>
                <h3 class="text-xl font-semibold mb-2">Wybierz temat</h3>
                <p class="text-muted-foreground">
                    Wybierz jeden z dostępnych tematów: historia, kultura, geografia lub język polski
                </p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-primary/10 flex items-center justify-center">
                    <span class="text-3xl font-bold text-primary">2</span>
                </div>
                <h3 class="text-xl font-semibold mb-2">Rozwiązuj quiz</h3>
                <p class="text-muted-foreground">
                    Odpowiadaj na pytania i otrzymuj natychmiastową informację zwrotną po każdej odpowiedzi
                </p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-primary/10 flex items-center justify-center">
                    <span class="text-3xl font-bold text-primary">3</span>
                </div>
                <h3 class="text-xl font-semibold mb-2">Śledź postępy</h3>
                <p class="text-muted-foreground">
                    Zobacz swoje wyniki i zidentyfikuj obszary wymagające dodatkowej nauki
                </p>
            </div>
        </div>
    </div>

    {{-- Features Section --}}
    <div class="mt-16 mb-16 overflow-hidden bg-gradient-card backdrop-blur-sm shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] border border-white/40 rounded-lg p-8 md:p-12 relative
         before:absolute before:inset-0 before:rounded-lg before:p-[1px] before:bg-gradient-to-br before:from-white/50 before:via-white/20 before:to-transparent before:-z-10">
        <h2 class="text-3xl md:text-4xl font-bold text-center bg-gradient-primary bg-clip-text text-transparent mb-12">
            Dlaczego warto wybrać QuizPolaka?
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="flex gap-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2">Aktualne pytania egzaminacyjne</h3>
                    <p class="text-muted-foreground">
                        Wszystkie pytania są oparte na oficjalnym programie egzaminu na Kartę Polaka
                    </p>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2">Natychmiastowa informacja zwrotna</h3>
                    <p class="text-muted-foreground">
                        Dowiedz się od razu, czy Twoja odpowiedź jest prawidłowa i ucz się na błędach
                    </p>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2">Ucz się w swoim tempie</h3>
                    <p class="text-muted-foreground">
                        Bez limitu czasowego - rozwiązuj quizy kiedy chcesz i ile razy chcesz
                    </p>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2">Dostępne wszędzie</h3>
                    <p class="text-muted-foreground">
                        Korzystaj z platformy na komputerze, tablecie lub smartfonie - gdzie tylko chcesz
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- FAQ Section --}}
    <div class="mt-16 mb-16">
        <h2 class="text-3xl md:text-4xl font-bold text-center bg-gradient-primary bg-clip-text text-transparent mb-12">
            Najczęściej zadawane pytania
        </h2>
        <div class="max-w-3xl mx-auto space-y-4" x-data="{ open: null }">
            {{-- FAQ Item 1 --}}
            <div class="overflow-hidden bg-gradient-card backdrop-blur-sm shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] border border-white/40 rounded-lg
                        before:absolute before:inset-0 before:rounded-lg before:p-[1px] before:bg-gradient-to-br before:from-white/50 before:via-white/20 before:to-transparent before:-z-10 relative">
                <button @click="open = open === 1 ? null : 1"
                        class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-primary/5 transition-colors">
                    <span class="font-semibold text-foreground">Czym jest Karta Polaka?</span>
                    <svg class="w-5 h-5 text-primary transition-transform duration-200" :class="{ 'rotate-180': open === 1 }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open === 1" x-collapse class="px-6 pb-4">
                    <p class="text-sm text-muted-foreground">
                        Karta Polaka to dokument potwierdzający przynależność do Narodu Polskiego, przyznawany osobom nieposiadającym polskiego obywatelstwa, które deklarują przynależność do Narodu Polskiego.
                    </p>
                </div>
            </div>

            {{-- FAQ Item 2 --}}
            <div class="overflow-hidden bg-gradient-card backdrop-blur-sm shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] border border-white/40 rounded-lg
                        before:absolute before:inset-0 before:rounded-lg before:p-[1px] before:bg-gradient-to-br before:from-white/50 before:via-white/20 before:to-transparent before:-z-10 relative">
                <button @click="open = open === 2 ? null : 2"
                        class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-primary/5 transition-colors">
                    <span class="font-semibold text-foreground">Jak przebiega rozmowa kwalifikacyjna?</span>
                    <svg class="w-5 h-5 text-primary transition-transform duration-200" :class="{ 'rotate-180': open === 2 }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open === 2" x-collapse class="px-6 pb-4">
                    <p class="text-sm text-muted-foreground">
                        Rozmowa kwalifikacyjna obejmuje pytania z zakresu historii, kultury i języka polskiego. Kandydat musi wykazać się podstawową znajomością tych dziedzin oraz umiejętnością porozumiewania się w języku polskim.
                    </p>
                </div>
            </div>

            {{-- FAQ Item 3 --}}
            <div class="overflow-hidden bg-gradient-card backdrop-blur-sm shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] border border-white/40 rounded-lg
                        before:absolute before:inset-0 before:rounded-lg before:p-[1px] before:bg-gradient-to-br before:from-white/50 before:via-white/20 before:to-transparent before:-z-10 relative">
                <button @click="open = open === 3 ? null : 3"
                        class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-primary/5 transition-colors">
                    <span class="font-semibold text-foreground">Czy QuizPolaka jest darmowy?</span>
                    <svg class="w-5 h-5 text-primary transition-transform duration-200" :class="{ 'rotate-180': open === 3 }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open === 3" x-collapse class="px-6 pb-4">
                    <p class="text-sm text-muted-foreground">
                        Tak! QuizPolaka jest w 100% darmowy. Wszystkie quizy, pytania i funkcje są dostępne bez żadnych opłat. Wystarczy założyć darmowe konto, aby rozpocząć naukę.
                    </p>
                </div>
            </div>

            {{-- FAQ Item 4 --}}
            <div class="overflow-hidden bg-gradient-card backdrop-blur-sm shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] border border-white/40 rounded-lg
                        before:absolute before:inset-0 before:rounded-lg before:p-[1px] before:bg-gradient-to-br before:from-white/50 before:via-white/20 before:to-transparent before:-z-10 relative">
                <button @click="open = open === 4 ? null : 4"
                        class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-primary/5 transition-colors">
                    <span class="font-semibold text-foreground">Ile razy mogę powtarzać quizy?</span>
                    <svg class="w-5 h-5 text-primary transition-transform duration-200" :class="{ 'rotate-180': open === 4 }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open === 4" x-collapse class="px-6 pb-4">
                    <p class="text-sm text-muted-foreground">
                        Możesz powtarzać quizy nieograniczoną ilość razy! Nauka przez powtarzanie jest jedną z najskuteczniejszych metod przygotowania do egzaminu. Im więcej ćwiczysz, tym lepiej zapamiętasz materiał.
                    </p>
                </div>
            </div>

            {{-- FAQ Item 5 --}}
            <div class="overflow-hidden bg-gradient-card backdrop-blur-sm shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] border border-white/40 rounded-lg
                        before:absolute before:inset-0 before:rounded-lg before:p-[1px] before:bg-gradient-to-br before:from-white/50 before:via-white/20 before:to-transparent before:-z-10 relative">
                <button @click="open = open === 5 ? null : 5"
                        class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-primary/5 transition-colors">
                    <span class="font-semibold text-foreground">Skąd pochodzą pytania w quizach?</span>
                    <svg class="w-5 h-5 text-primary transition-transform duration-200" :class="{ 'rotate-180': open === 5 }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open === 5" x-collapse class="px-6 pb-4">
                    <p class="text-sm text-muted-foreground">
                        Wszystkie pytania są oparte na oficjalnym programie egzaminu na Kartę Polaka i obejmują najważniejsze zagadnienia z historii, kultury, geografii i języka polskiego, które są najczęściej poruszane podczas rozmowy kwalifikacyjnej.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="mt-16 bg-gradient-card backdrop-blur-sm border border-white/40 rounded-t-lg overflow-hidden relative
                   shadow-[0_-10px_40px_-10px_rgba(0,0,0,0.15)]
                   before:absolute before:inset-0 before:rounded-t-3xl before:p-[1px] before:bg-gradient-to-br before:from-white/50 before:via-white/20 before:to-transparent before:-z-10">
        <div class="container mx-auto px-4 py-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                {{-- Left Column --}}
                <div class="flex flex-col items-center md:items-start gap-4">
                    <div class="inline-flex items-center gap-2 px-2.5 py-1.5 rounded-full bg-primary/10 border border-primary/20">
                        @svg('lucide-shield-check', 'w-3.5 h-3.5 text-primary')
                        <span class="text-[10px] font-medium text-foreground">Bezpieczne płatności przez Stripe</span>
                    </div>

                    <div class="text-center md:text-left space-y-2">
                        <p class="text-xs text-muted-foreground">
                            © {{ date('Y') }} QuizPolaka. Wszelkie prawa zastrzeżone.
                        </p>
                        <div class="flex flex-wrap justify-center md:justify-start gap-4 text-xs">
                            <a href="#" class="text-muted-foreground hover:text-primary transition-colors">
                                Polityka prywatności
                            </a>
                            <a href="#" class="text-muted-foreground hover:text-primary transition-colors">
                                Regulamin
                            </a>
                            <a href="#" class="text-muted-foreground hover:text-primary transition-colors">
                                Cookies
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="flex flex-wrap justify-center md:justify-end gap-3">
                    <a href="{{ route('topics') }}" wire:navigate
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-primary text-white text-sm font-semibold rounded-lg
                              shadow-glow hover:shadow-[0_15px_50px_-15px_hsl(var(--primary))] transition-all duration-300 hover:scale-105">
                        Przeglądaj tematy
                        @svg('lucide-arrow-right', 'w-4 h-4')
                    </a>
                    @guest
                        <a href="{{ route('register') }}" wire:navigate
                           class="inline-flex items-center gap-2 px-4 py-2 bg-white text-primary text-sm font-semibold rounded-lg border-2 border-primary
                                  hover:bg-primary/5 transition-all duration-300 hover:scale-105">
                            Załóż konto za darmo
                            @svg('lucide-user-plus', 'w-4 h-4')
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </footer>

</div>
