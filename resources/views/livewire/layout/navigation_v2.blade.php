<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

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

<header class="w-full bg-card/80 backdrop-blur-sm border-b border-border sticky top-0 z-50 rounded-b-lg" x-data="{ mobileOpen: false }">
    <div class="container mx-auto px-4 h-16 flex items-center justify-between ">
        <!-- App name on the left -->
        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center">
            <h1 class="text-xl font-bold bg-gradient-primary bg-clip-text text-transparent">
                QuizPolaka <span style="font-size: 1rem; font-weight: 400" class="hidden md:inline">Testy  rozmowy karty Polaka</span>
            </h1>
        </a>

        <!-- Language toggle and auth buttons on the right -->
        <div class="hidden sm:flex sm:items-center sm:gap-4">
            <!-- Language Dropdown -->
            <div x-data="{ open: false, currentLang: 'PL' }" class="relative">
                <button @click="open = !open"
                        class="flex items-center gap-2 text-muted-foreground hover:text-foreground text-sm font-medium hidden">
                    <i data-lucide="globe" class="w-4 h-4"></i>
                    <span x-text="currentLang || 'PL'">PL</span>                     <i data-lucide="chevron-down" class="w-3 h-3"></i>

                </button>
                <div x-show="open" @click.outside="open = false"
                     class="absolute right-0 mt-2 w-40 bg-popover border border-border shadow-lg z-50 rounded">
                    <button @click="currentLang = 'PL'" class="flex items-center gap-2 px-4 py-2 hover:bg-accent w-full">
                        🇵🇱 Polski
                    </button>
                    <button @click="currentLang = 'RU'" class="flex items-center gap-2 px-4 py-2 hover:bg-accent w-full">
                        🇷🇺 Русский
                    </button>
                    <button @click="currentLang = 'UA'" class="flex items-center gap-2 px-4 py-2 hover:bg-accent w-full">
                        🇺🇦 Українська
                    </button>
                    <button @click="currentLang = 'BY'" class="flex items-center gap-2 px-4 py-2 hover:bg-accent w-full">
                        🇧🇾 Беларуская
                    </button>
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
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
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
        @if(Auth::check())
            <div class="px-4">
                <div class="font-medium text-base text-gray-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"></div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        @else
            <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')" wire:navigate>
                {{ __('Login') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')" wire:navigate>
                {{ __('Register') }}
            </x-responsive-nav-link>

        @endif
    </div>
</header>
