<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app-kp')] class extends Component
{
    public function mount(): void
    {
        if (! auth()->check() || ! auth()->user()->isAdmin()) {
            abort(404);
        }
    }
}; ?>

@assets
@vite(['resources/css/main.scss'])
@endassets

<div>
    {{-- Hero Section --}}
    <div class="mt-8 mb-16 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 border border-primary/20 mb-6">
            @svg('lucide-sparkles', 'w-4 h-4 text-primary')
            <span class="text-sm font-semibold text-primary">Odblokuj pełen potencjał</span>
        </div>

        <h1 class="text-4xl md:text-6xl font-bold bg-gradient-primary bg-clip-text text-transparent mb-4">
            Przejdź na wyższy poziom
        </h1>
        <p class="text-xl md:text-2xl text-foreground/80 mb-6">
            Wybierz plan idealny dla Ciebie
        </p>
        <p class="text-base md:text-lg text-muted-foreground max-w-2xl mx-auto">
            Rozpocznij za darmo lub odblokuj zaawansowane funkcje, aby maksymalnie wykorzystać swoją naukę
        </p>
    </div>

    {{-- Pricing Comparison Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto mb-16">

        {{-- Free Plan --}}
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-lg border-2 border-gray-200 dark:border-gray-700 p-8 transition-all duration-300 hover:shadow-xl">
            <div class="mb-6">
                <h3 class="text-2xl font-bold text-foreground mb-2">Darmowy</h3>
                <div class="flex items-baseline gap-2 mb-4">
                    <span class="text-5xl font-bold text-foreground">0 zł</span>
                    <span class="text-muted-foreground">/miesiąc</span>
                </div>
                <p class="text-muted-foreground">Idealne na początek Twojej przygody z nauką</p>
            </div>

            <div class="space-y-4 mb-8">
                <div class="flex items-start gap-3">
                    @svg('lucide-check', 'w-5 h-5 text-green-500 flex-shrink-0 mt-0.5')
                    <span class="text-foreground">Dostęp do wszystkich tematów</span>
                </div>
                <div class="flex items-start gap-3">
                    @svg('lucide-check', 'w-5 h-5 text-green-500 flex-shrink-0 mt-0.5')
                    <span class="text-foreground">Setki pytań egzaminacyjnych</span>
                </div>
                <div class="flex items-start gap-3">
                    @svg('lucide-check', 'w-5 h-5 text-green-500 flex-shrink-0 mt-0.5')
                    <span class="text-foreground">Natychmiastowa informacja zwrotna</span>
                </div>
                <div class="flex items-start gap-3">
                    @svg('lucide-check', 'w-5 h-5 text-green-500 flex-shrink-0 mt-0.5')
                    <span class="text-foreground">Śledzenie postępów</span>
                </div>
                <div class="flex items-start gap-3">
                    @svg('lucide-x', 'w-5 h-5 text-gray-300 flex-shrink-0 mt-0.5')
                    <span class="text-muted-foreground line-through">Quiz głosowy AI</span>
                </div>
                <div class="flex items-start gap-3">
                    @svg('lucide-x', 'w-5 h-5 text-gray-300 flex-shrink-0 mt-0.5')
                    <span class="text-muted-foreground line-through">Spersonalizowane pytania</span>
                </div>
                <div class="flex items-start gap-3">
                    @svg('lucide-x', 'w-5 h-5 text-gray-300 flex-shrink-0 mt-0.5')
                    <span class="text-muted-foreground line-through">Zaawansowana analityka</span>
                </div>
                <div class="flex items-start gap-3">
                    @svg('lucide-x', 'w-5 h-5 text-gray-300 flex-shrink-0 mt-0.5')
                    <span class="text-muted-foreground line-through">Wsparcie priorytetowe</span>
                </div>
            </div>

            <a href="{{ route('register') }}" wire:navigate
               class="block w-full text-center px-6 py-3 bg-gray-100 dark:bg-gray-700 text-foreground font-semibold rounded-lg border-2 border-gray-300 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-300">
                Zacznij za darmo
            </a>
        </div>

        {{-- Premium Plan - Featured --}}
        <div class="relative bg-gradient-to-br from-primary/5 via-purple-500/5 to-primary/5 rounded-2xl shadow-2xl border-2 border-primary p-8 transition-all duration-300 hover:scale-[1.02] hover:shadow-[0_20px_60px_-15px_hsl(var(--primary))]">
            {{-- Popular Badge --}}
            <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gradient-primary text-white text-sm font-bold shadow-glow">
                    @svg('lucide-crown', 'w-4 h-4')
                    <span>Najpopularniejszy</span>
                </div>
            </div>

            <div class="mb-6 mt-4">
                <h3 class="text-2xl font-bold text-foreground mb-2">Premium</h3>
                <div class="flex items-baseline gap-2 mb-4">
                    <span class="text-5xl font-bold bg-gradient-primary bg-clip-text text-transparent">29 zł</span>
                    <span class="text-muted-foreground">/miesiąc</span>
                </div>
                <p class="text-muted-foreground">Odblokuj pełen potencjał swojej nauki</p>
            </div>

            <div class="space-y-4 mb-8">
                <div class="flex items-start gap-3">
                    @svg('lucide-check', 'w-5 h-5 text-primary flex-shrink-0 mt-0.5')
                    <span class="text-foreground font-medium">Wszystko z planu Darmowego</span>
                </div>
                <div class="flex items-start gap-3">
                    @svg('lucide-check', 'w-5 h-5 text-primary flex-shrink-0 mt-0.5')
                    <div>
                        <span class="text-foreground font-medium">Quiz głosowy AI</span>
                        <p class="text-sm text-muted-foreground">Ćwicz z interaktywnym asystentem głosowym</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    @svg('lucide-check', 'w-5 h-5 text-primary flex-shrink-0 mt-0.5')
                    <div>
                        <span class="text-foreground font-medium">Spersonalizowane pytania</span>
                        <p class="text-sm text-muted-foreground">AI generuje pytania dopasowane do Ciebie</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    @svg('lucide-check', 'w-5 h-5 text-primary flex-shrink-0 mt-0.5')
                    <div>
                        <span class="text-foreground font-medium">Zaawansowana analityka</span>
                        <p class="text-sm text-muted-foreground">Szczegółowe statystyki i raporty postępów</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    @svg('lucide-check', 'w-5 h-5 text-primary flex-shrink-0 mt-0.5')
                    <div>
                        <span class="text-foreground font-medium">Wsparcie priorytetowe</span>
                        <p class="text-sm text-muted-foreground">Szybka pomoc od naszego zespołu</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    @svg('lucide-check', 'w-5 h-5 text-primary flex-shrink-0 mt-0.5')
                    <div>
                        <span class="text-foreground font-medium">Bez reklam</span>
                        <p class="text-sm text-muted-foreground">Nauka bez rozpraszaczy</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    @svg('lucide-check', 'w-5 h-5 text-primary flex-shrink-0 mt-0.5')
                    <div>
                        <span class="text-foreground font-medium">Wczesny dostęp</span>
                        <p class="text-sm text-muted-foreground">Pierwsi testują nowe funkcje</p>
                    </div>
                </div>
            </div>

            @auth
                <a href="{{ route('subscribe') }}"
                   class="block w-full text-center px-6 py-3 bg-gradient-primary text-white font-semibold rounded-lg shadow-glow hover:shadow-[0_15px_50px_-15px_hsl(var(--primary))] transition-all duration-300 hover:scale-105">
                    Przejdź na Premium
                </a>
            @else
                <a href="{{ route('register') }}" wire:navigate
                   class="block w-full text-center px-6 py-3 bg-gradient-primary text-white font-semibold rounded-lg shadow-glow hover:shadow-[0_15px_50px_-15px_hsl(var(--primary))] transition-all duration-300 hover:scale-105">
                    Rozpocznij teraz
                </a>
            @endauth
        </div>
    </div>

    {{-- Feature Comparison Table --}}
    <div class="max-w-5xl mx-auto mb-16">
        <h2 class="text-3xl md:text-4xl font-bold text-center bg-gradient-primary bg-clip-text text-transparent mb-12">
            Szczegółowe porównanie funkcji
        </h2>

        <div class="overflow-hidden bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Funkcja</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-foreground">Darmowy</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-primary">Premium</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-foreground">Wszystkie tematy quizów</td>
                            <td class="px-6 py-4 text-center">@svg('lucide-check', 'w-5 h-5 text-green-500 mx-auto')</td>
                            <td class="px-6 py-4 text-center">@svg('lucide-check', 'w-5 h-5 text-primary mx-auto')</td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-foreground">Liczba pytań</td>
                            <td class="px-6 py-4 text-center text-sm text-muted-foreground">Nieograniczona</td>
                            <td class="px-6 py-4 text-center text-sm text-primary font-semibold">Nieograniczona</td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-foreground">Śledzenie postępów</td>
                            <td class="px-6 py-4 text-center">@svg('lucide-check', 'w-5 h-5 text-green-500 mx-auto')</td>
                            <td class="px-6 py-4 text-center">@svg('lucide-check', 'w-5 h-5 text-primary mx-auto')</td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-foreground">Natychmiastowa informacja zwrotna</td>
                            <td class="px-6 py-4 text-center">@svg('lucide-check', 'w-5 h-5 text-green-500 mx-auto')</td>
                            <td class="px-6 py-4 text-center">@svg('lucide-check', 'w-5 h-5 text-primary mx-auto')</td>
                        </tr>
                        <tr class="bg-primary/5 hover:bg-primary/10 transition-colors">
                            <td class="px-6 py-4 text-sm font-semibold text-foreground">Quiz głosowy AI</td>
                            <td class="px-6 py-4 text-center">@svg('lucide-x', 'w-5 h-5 text-gray-300 mx-auto')</td>
                            <td class="px-6 py-4 text-center">@svg('lucide-check', 'w-5 h-5 text-primary mx-auto')</td>
                        </tr>
                        <tr class="bg-primary/5 hover:bg-primary/10 transition-colors">
                            <td class="px-6 py-4 text-sm font-semibold text-foreground">Spersonalizowane pytania AI</td>
                            <td class="px-6 py-4 text-center">@svg('lucide-x', 'w-5 h-5 text-gray-300 mx-auto')</td>
                            <td class="px-6 py-4 text-center">@svg('lucide-check', 'w-5 h-5 text-primary mx-auto')</td>
                        </tr>
                        <tr class="bg-primary/5 hover:bg-primary/10 transition-colors">
                            <td class="px-6 py-4 text-sm font-semibold text-foreground">Zaawansowana analityka</td>
                            <td class="px-6 py-4 text-center">@svg('lucide-x', 'w-5 h-5 text-gray-300 mx-auto')</td>
                            <td class="px-6 py-4 text-center">@svg('lucide-check', 'w-5 h-5 text-primary mx-auto')</td>
                        </tr>
                        <tr class="bg-primary/5 hover:bg-primary/10 transition-colors">
                            <td class="px-6 py-4 text-sm font-semibold text-foreground">Wsparcie priorytetowe</td>
                            <td class="px-6 py-4 text-center">@svg('lucide-x', 'w-5 h-5 text-gray-300 mx-auto')</td>
                            <td class="px-6 py-4 text-center">@svg('lucide-check', 'w-5 h-5 text-primary mx-auto')</td>
                        </tr>
                        <tr class="bg-primary/5 hover:bg-primary/10 transition-colors">
                            <td class="px-6 py-4 text-sm font-semibold text-foreground">Bez reklam</td>
                            <td class="px-6 py-4 text-center">@svg('lucide-x', 'w-5 h-5 text-gray-300 mx-auto')</td>
                            <td class="px-6 py-4 text-center">@svg('lucide-check', 'w-5 h-5 text-primary mx-auto')</td>
                        </tr>
                        <tr class="bg-primary/5 hover:bg-primary/10 transition-colors">
                            <td class="px-6 py-4 text-sm font-semibold text-foreground">Wczesny dostęp do nowych funkcji</td>
                            <td class="px-6 py-4 text-center">@svg('lucide-x', 'w-5 h-5 text-gray-300 mx-auto')</td>
                            <td class="px-6 py-4 text-center">@svg('lucide-check', 'w-5 h-5 text-primary mx-auto')</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- CTA Section --}}
    <div class="max-w-4xl mx-auto mb-16">
        <div class="relative overflow-hidden bg-gradient-to-br from-primary via-purple-600 to-primary rounded-3xl shadow-2xl p-12 text-center text-white">
            {{-- Background Pattern --}}
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;"></div>
            </div>

            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/20 backdrop-blur-sm mb-6">
                    @svg('lucide-zap', 'w-4 h-4')
                    <span class="text-sm font-semibold">Specjalna oferta</span>
                </div>

                <h2 class="text-3xl md:text-4xl font-bold mb-4">
                    Gotowy, aby odblokować pełen potencjał?
                </h2>
                <p class="text-lg mb-8 text-white/90 max-w-2xl mx-auto">
                    Dołącz do tysięcy uczniów, którzy już osiągnęli sukces dzięki naszym zaawansowanym funkcjom
                </p>

                <div class="flex flex-wrap justify-center gap-4">
                    @auth
                        <a href="{{ route('subscribe') }}"
                           class="inline-flex items-center gap-2 px-8 py-4 bg-white text-primary font-bold text-lg rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                            @svg('lucide-rocket', 'w-5 h-5')
                            <span>Zacznij teraz za 29 zł/miesiąc</span>
                        </a>
                    @else
                        <a href="{{ route('register') }}" wire:navigate
                           class="inline-flex items-center gap-2 px-8 py-4 bg-white text-primary font-bold text-lg rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                            @svg('lucide-rocket', 'w-5 h-5')
                            <span>Utwórz darmowe konto</span>
                        </a>
                    @endauth

                    <a href="{{ route('topics') }}" wire:navigate
                       class="inline-flex items-center gap-2 px-8 py-4 bg-white/10 backdrop-blur-sm text-white font-semibold text-lg rounded-xl border-2 border-white/30 hover:bg-white/20 transition-all duration-300">
                        @svg('lucide-play', 'w-5 h-5')
                        <span>Wypróbuj za darmo</span>
                    </a>
                </div>

                <p class="mt-6 text-sm text-white/80">
                    Anuluj w każdej chwili • Bezpieczne płatności • Bez ukrytych opłat
                </p>
            </div>
        </div>
    </div>

    {{-- FAQ Section --}}
    <div class="max-w-3xl mx-auto mb-16">
        <h2 class="text-3xl md:text-4xl font-bold text-center bg-gradient-primary bg-clip-text text-transparent mb-12">
            Najczęściej zadawane pytania
        </h2>

        <div class="space-y-4" x-data="{ open: null }">
            {{-- FAQ Item 1 --}}
            <div class="overflow-hidden bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 relative">
                <button @click="open = open === 1 ? null : 1"
                        class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                    <span class="font-semibold text-foreground">Czy mogę anulować subskrypcję w każdej chwili?</span>
                    <svg class="w-5 h-5 text-primary transition-transform duration-200" :class="{ 'rotate-180': open === 1 }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open === 1" x-collapse class="px-6 pb-4">
                    <p class="text-sm text-muted-foreground">
                        Tak! Możesz anulować subskrypcję Premium w dowolnym momencie. Nie ma żadnych zobowiązań ani kar za anulowanie.
                    </p>
                </div>
            </div>

            {{-- FAQ Item 2 --}}
            <div class="overflow-hidden bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 relative">
                <button @click="open = open === 2 ? null : 2"
                        class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                    <span class="font-semibold text-foreground">Jakie metody płatności akceptujecie?</span>
                    <svg class="w-5 h-5 text-primary transition-transform duration-200" :class="{ 'rotate-180': open === 2 }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open === 2" x-collapse class="px-6 pb-4">
                    <p class="text-sm text-muted-foreground">
                        Akceptujemy wszystkie główne karty kredytowe i debetowe poprzez bezpieczny system płatności Stripe. Wszystkie transakcje są w pełni zabezpieczone.
                    </p>
                </div>
            </div>

            {{-- FAQ Item 3 --}}
            <div class="overflow-hidden bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 relative">
                <button @click="open = open === 3 ? null : 3"
                        class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                    <span class="font-semibold text-foreground">Co to jest quiz głosowy AI?</span>
                    <svg class="w-5 h-5 text-primary transition-transform duration-200" :class="{ 'rotate-180': open === 3 }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open === 3" x-collapse class="px-6 pb-4">
                    <p class="text-sm text-muted-foreground">
                        Quiz głosowy AI to interaktywna funkcja, w której możesz rozmawiać z asystentem AI w języku polskim. To świetny sposób na ćwiczenie wymowy i rozumienia ze słuchu - idealny do przygotowania do rozmowy kwalifikacyjnej!
                    </p>
                </div>
            </div>

            {{-- FAQ Item 4 --}}
            <div class="overflow-hidden bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 relative">
                <button @click="open = open === 4 ? null : 4"
                        class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                    <span class="font-semibold text-foreground">Czy plan darmowy jest naprawdę za darmo?</span>
                    <svg class="w-5 h-5 text-primary transition-transform duration-200" :class="{ 'rotate-180': open === 4 }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open === 4" x-collapse class="px-6 pb-4">
                    <p class="text-sm text-muted-foreground">
                        Tak! Plan darmowy jest w pełni darmowy, bez ukrytych opłat. Możesz korzystać z wszystkich podstawowych funkcji bez limitu czasu. Nie potrzebujesz karty kredytowej, aby się zarejestrować.
                    </p>
                </div>
            </div>

            {{-- FAQ Item 5 --}}
            <div class="overflow-hidden bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 relative">
                <button @click="open = open === 5 ? null : 5"
                        class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                    <span class="font-semibold text-foreground">Czy mogę zmienić plan później?</span>
                    <svg class="w-5 h-5 text-primary transition-transform duration-200" :class="{ 'rotate-180': open === 5 }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open === 5" x-collapse class="px-6 pb-4">
                    <p class="text-sm text-muted-foreground">
                        Oczywiście! Możesz przejść na plan Premium w dowolnym momencie lub wrócić do planu darmowego, jeśli zdecydujesz, że nie potrzebujesz zaawansowanych funkcji.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
