<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<header class="w-full bg-card/80 backdrop-blur-sm border-b border-border sticky top-0 z-50 rounded-b-lg"
        x-data="{
            mobileOpen: false,
            theme: localStorage.getItem('theme') || 'purple',
            themes: ['purple', 'indigo', 'polish'],
            themeNames: {
                'purple': 'Purple & Coral',
                'indigo': 'Indigo & Rose',
                'polish': 'Polish Red'
            },
            init() {
                document.documentElement.setAttribute('data-theme', this.theme);
            },
            toggleTheme() {
                const currentIndex = this.themes.indexOf(this.theme);
                const nextIndex = (currentIndex + 1) % this.themes.length;
                this.theme = this.themes[nextIndex];
                localStorage.setItem('theme', this.theme);
                document.documentElement.setAttribute('data-theme', this.theme);
            },
            getThemeLabel() {
                return this.themeNames[this.theme] || this.theme;
            }
        }">
    <div class="container_ mx-auto px-4 h-16 flex items-center justify-between ">
        <!-- App name on the left -->
        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center">

            <h1 class="text-xl font-bold bg-gradient-primary bg-clip-text text-transparent">
                QuizPolaka <span style="font-size: 1rem; font-weight: 400" class="hidden xl:inline">{{ __('app.polish_card_tests') }}</span>
            </h1>
        </a>

        <!-- Language toggle and auth buttons on the right -->
        <div class="hidden sm:flex sm:items-center sm:gap-4">
            @if(Auth::check() && Auth::user()->isAdmin())
                <a href="/admin" target="_blank" class="flex items-center gap-2 text-muted-foreground hover:text-foreground text-sm font-medium px-3 py-2 rounded-md hover:bg-accent transition-colors">
                    @svg('lucide-shield', 'w-4 h-4')
                    <span>Admin</span>
                </a>
            @endif

            <a href="{{ route('ai-sync-configure') }}" wire:navigate
               class="group relative flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-lg border-2 border-primary/30
                      bg-primary/5 hover:bg-primary/10 text-primary hover:text-primary transition-all duration-300
                      hover:border-primary/60 hover:shadow-[0_0_20px_rgba(var(--primary-rgb),0.3)]"
               style="animation: pulse-border 2s ease-in-out infinite;">
                @svg('lucide-mic', 'w-4 h-4 group-hover:scale-110 transition-transform')
                <span class="font-semibold">AI Quiz</span>

            </a>

            <!-- Theme Toggle Button -->
            <button @click="toggleTheme()"
                    class="flex items-center gap-2 text-muted-foreground hover:text-foreground text-sm font-medium px-3 py-2 rounded-md hover:bg-accent transition-colors"
                    x-bind:title="'Theme: ' + getThemeLabel()">
                @svg('lucide-palette', 'w-4 h-4')
                <span class="hidden md:inline" x-text="getThemeLabel()"></span>
            </button>

            <!-- Language Dropdown -->
            @php
                $currentLocale = LaravelLocalization::getCurrentLocale();
                $localeNames = [
                    'pl' => ['name' => 'Polski', 'flag' => '🇵🇱', 'short' => 'PL'],
                          'uk' => ['name' => 'Українська', 'flag' => '🇺🇦', 'short' => 'UA'],
                    'be' => ['name' => 'Беларуская','short' => 'BY'],
                    'ru' => ['name' => 'Русский', 'short' => 'RU'],
                ];
            @endphp
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                        class="flex items-center gap-2 text-muted-foreground hover:text-foreground text-sm font-medium">
                    @svg('lucide-globe', 'w-4 h-4')
                    <span>{{ $localeNames[$currentLocale]['short'] ?? 'PL' }}</span>
                    @svg('lucide-chevron-down', 'w-3 h-3')
                </button>
                <div x-show="open" @click.outside="open = false"
                     class="absolute right-0 mt-2 w-40 bg-popover border border-border shadow-lg z-50 rounded">
                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                           class="flex items-center gap-2 px-4 py-2 hover:bg-accent w-full text-left {{ $currentLocale == $localeCode ? 'bg-accent' : '' }}">
                            {{ $localeNames[$localeCode]['flag'] ?? '🏳️' }} {{ $localeNames[$localeCode]['name'] ?? $properties['native'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            @if(Auth::check())
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            <span class="flex items-center gap-2">
                                @svg('lucide-user', 'w-4 h-4 text-blue-500')
                                {{ __('Profile') }}
                            </span>
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('analytics')" wire:navigate>
                            <span class="flex items-center gap-2">
                                @svg('lucide-bar-chart-3', 'w-4 h-4 text-green-500')
                                {{ __('Analytics') }}
                            </span>
                        </x-dropdown-link>
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                <span class="flex items-center gap-2">
                                    @svg('lucide-log-out', 'w-4 h-4 text-red-500')
                                    {{ __('Log Out') }}
                                </span>
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            @else
                <x-nav-link :href="route('login')" :active="request()->routeIs('login')" wire:navigate>
                    {{ __('Login') }}
                </x-nav-link>
                <x-nav-link :href="route('register')" :active="request()->routeIs('register')" wire:navigate class="text-white">
                    <button class="bg-gradient-primary hover:shadow-glow transition-all duration-300 px-4 py-2  rounded-md">
                        {{ __('Register') }}
                    </button>
                </x-nav-link>
            @endif
        </div>

        <!-- Hamburger for mobile -->
        <div class="-me-2 flex items-center sm:hidden">
            <button @click="mobileOpen = ! mobileOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': mobileOpen, 'inline-flex': ! mobileOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! mobileOpen, 'inline-flex': mobileOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Responsive menu -->
    <div :class="{'block': mobileOpen, 'hidden': ! mobileOpen}" class="hidden sm:hidden pt-4 pb-1 border-t border-gray-200">
        <!-- Theme Toggle -->
        <div class="px-4 pb-3">
            <button @click="toggleTheme()"
                    class="flex items-center gap-2 w-full text-muted-foreground hover:text-foreground text-sm font-medium px-3 py-2 rounded-md hover:bg-accent transition-colors">
                @svg('lucide-palette', 'w-4 h-4')
                <span x-text="getThemeLabel()"></span>
            </button>
        </div>

        <!-- Language Selector -->
        @php
            $currentLocale = LaravelLocalization::getCurrentLocale();
            $localeNames = [
                'pl' => ['name' => 'Polski', 'flag' => '🇵🇱', 'short' => 'PL'],
                'uk' => ['name' => 'Українська', 'flag' => '🇺🇦', 'short' => 'UA'],
                'be' => ['name' => 'Беларуская','short' => 'BY'],
                'ru' => ['name' => 'Русский', 'short' => 'RU'],
            ];
        @endphp
        <div class="px-4 pb-3" x-data="{ langOpen: false }">
            <button @click="langOpen = !langOpen"
                    class="flex items-center justify-between gap-2 w-full text-muted-foreground hover:text-foreground text-sm font-medium px-3 py-2 rounded-md hover:bg-accent transition-colors">
                <div class="flex items-center gap-2">
                    @svg('lucide-globe', 'w-4 h-4')
                    <span>{{ $localeNames[$currentLocale]['flag'] ?? '🏳️' }} {{ $localeNames[$currentLocale]['name'] ?? 'Language' }}</span>
                </div>
                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': langOpen }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </button>
            <div x-show="langOpen" x-collapse class="mt-1 space-y-1">
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                       class="flex items-center gap-2 px-6 py-2 rounded-md hover:bg-accent w-full text-left text-sm {{ $currentLocale == $localeCode ? 'bg-accent' : '' }}">
                        {{ $localeNames[$localeCode]['flag'] ?? '🏳️' }} {{ $localeNames[$localeCode]['name'] ?? $properties['native'] }}
                    </a>
                @endforeach
            </div>
        </div>

        @if(Auth::check())
            <div class="px-4 border-t border-gray-200 pt-3">
                <div class="font-medium text-base text-gray-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"></div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                @if(Auth::user()->isAdmin())
                    <x-responsive-nav-link href="/admin" target="_blank">
                        @svg('lucide-shield', 'w-4 h-4 inline-block mr-2')
                        {{ __('Admin') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('ai-sync-configure')" wire:navigate>
                        @svg('lucide-mic', 'w-4 h-4 inline-block mr-2')
                        AI Quiz
                        <span class="ml-2 px-2 py-0.5 text-xs font-semibold bg-primary text-primary-foreground rounded-full">New</span>
                    </x-responsive-nav-link>
                @endif
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    @svg('lucide-user', 'w-4 h-4 inline-block mr-2 text-blue-500')
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('analytics')" wire:navigate>
                    @svg('lucide-bar-chart-3', 'w-4 h-4 inline-block mr-2 text-green-500')
                    {{ __('Analytics') }}
                </x-responsive-nav-link>
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        @svg('lucide-log-out', 'w-4 h-4 inline-block mr-2 text-red-500')
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        @else
            <div class="border-t border-gray-200 pt-3">
                <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')" wire:navigate>
                    {{ __('Login') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')" wire:navigate>
                    {{ __('Register') }}
                </x-responsive-nav-link>
            </div>
        @endif
    </div>
</header>
