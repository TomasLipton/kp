<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app-kp')]
class extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-[calc(100vh-4rem)] flex items-center justify-center py-8 px-4">
    <main class="w-full max-w-6xl">
        <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
            <!-- Left: Features -->
            <div class="space-y-8">
                <div>
                    <h1 class="text-4xl lg:text-5xl font-bold mb-4">
                        {{ __('app.register_headline') }} <span class="text-primary">{{ __('app.register_headline_highlight') }}</span> {{ __('app.register_headline_end') }}
                    </h1>
                    <p class="text-lg text-muted-foreground">
                        {{ __('app.register_description') }}
                    </p>
                </div>

                <!-- Features Grid -->
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-1">{{ __('app.ai_generated_tests') }}</h3>
                            <p class="text-sm text-muted-foreground">{{ __('app.ai_generated_tests_desc') }}</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-1">{{ __('app.adaptive_learning') }}</h3>
                            <p class="text-sm text-muted-foreground">{{ __('app.adaptive_learning_desc') }}</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-1">{{ __('app.track_progress') }}</h3>
                            <p class="text-sm text-muted-foreground">{{ __('app.track_progress_desc') }}</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-1">{{ __('app.practice_anytime') }}</h3>
                            <p class="text-sm text-muted-foreground">{{ __('app.practice_anytime_desc') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
{{--                <div class="flex gap-8 pt-4">--}}
{{--                    <div>--}}
{{--                        <div class="text-3xl font-bold text-primary">10k+</div>--}}
{{--                        <div class="text-sm text-muted-foreground">{{ __('app.active_learners') }}</div>--}}
{{--                    </div>--}}
{{--                    <div>--}}
{{--                        <div class="text-3xl font-bold text-primary">50k+</div>--}}
{{--                        <div class="text-sm text-muted-foreground">{{ __('app.tests_completed') }}</div>--}}
{{--                    </div>--}}
{{--                    <div>--}}
{{--                        <div class="text-3xl font-bold text-primary">95%</div>--}}
{{--                        <div class="text-sm text-muted-foreground">{{ __('app.success_rate') }}</div>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>

            <!-- Right: Registration Form -->
            <div class="lg:pl-8">
                <div class="p-6 bg-gradient-card shadow-lg border border-gray-200 dark:border-gray-800 rounded-xl max-w-md mx-auto">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold mb-2">{{ __('app.create_new_account') }}</h2>
                        <p class="text-sm text-muted-foreground">{{ __('app.start_learning_journey') }}</p>
                    </div>

                    <form id="registerForm" method="GET" action="{{ route('auth.google.redirect') }}" onsubmit="return validateForm()">
                        @csrf

                        <input type="hidden" name="privacy_accepted" value="{{ old('privacy_accepted', '0') }}">
                        <input type="hidden" name="rules_accepted" value="{{ old('rules_accepted', '0') }}">

                        <button
                            type="submit"
                            name="provider"
                            value="google"
                            class="w-full py-3 text-sm font-medium border-2 border-gray-200 dark:border-gray-700 rounded-lg flex items-center justify-center hover:border-primary hover:bg-primary/5 transition-all group mb-6"
                        >
                            <svg class="w-5 h-5 mr-2 transition-transform group-hover:scale-110" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            {{ __('Register with Google') }}
                        </button>

                        {{-- Telegram Login Widget --}}
                        <div class="relative w-full py-2 px-3 rounded-lg flex items-center justify-center transition-all mb-4">
                            <script async src="https://telegram.org/js/telegram-widget.js?22" data-telegram-login="quiz_polaka_bot" data-size="large" data-radius="12" data-auth-url="{{ route('auth.telegram.callback') }}"></script>

                            {{-- Overlay to block interaction until checkboxes are accepted --}}
                            <div id="telegram-overlay" class="group absolute inset-0 bg-transparent hover:bg-gray-500/20 hover:dark:bg-gray-900/30 backdrop-blur-0 hover:backdrop-blur-sm rounded-lg cursor-pointer transition-all duration-300 border-2 border-transparent hover:border-[#0088cc] flex items-center justify-center" onclick="handleOverlayClick()">
                                <span class="opacity-0 group-hover:opacity-100 text-xs font-medium text-gray-700 dark:text-gray-300 bg-white/90 dark:bg-gray-800/90 px-3 py-1.5 rounded-full shadow-sm transition-opacity duration-300">
                                    {{ __('app.must_accept_conditions') }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-2 mb-4">
                            <div>
                                <label id="privacy-label" class="flex items-start gap-2 text-xs cursor-pointer p-2 rounded-lg transition-colors">
                                    <input
                                        type="checkbox"
                                        id="privacy"
                                        name="privacy_accepted"
                                        value="1"
                                        class="mt-0.5 rounded border-gray-300 text-primary focus:ring-primary focus:ring-offset-0"
                                        {{ old('privacy_accepted') ? 'checked' : '' }}
                                        onchange="handleCheckboxChange(this, 'privacy')"
                                    >
                                    <span>I accept the <a href="#" class="text-primary hover:underline">{{ __('app.privacy_policy') }}</a></span>
                                </label>
                                <p id="privacy-error" class="text-xs text-red-600 dark:text-red-400 ml-6 mt-1 hidden">
                                    {{ __('app.privacy_required') }}
                                </p>
                            </div>

                            <div>
                                <label id="rules-label" class="flex items-start gap-2 text-xs cursor-pointer p-2 rounded-lg transition-colors">
                                    <input
                                        type="checkbox"
                                        id="rules"
                                        name="rules_accepted"
                                        value="1"
                                        class="mt-0.5 rounded border-gray-300 text-primary focus:ring-primary focus:ring-offset-0"
                                        {{ old('rules_accepted') ? 'checked' : '' }}
                                        onchange="handleCheckboxChange(this, 'rules')"
                                    >
                                    <span>I accept the <a href="#" class="text-primary hover:underline">{{ __('app.terms_of_service') }}</a></span>
                                </label>
                                <p id="rules-error" class="text-xs text-red-600 dark:text-red-400 ml-6 mt-1 hidden">
                                    {{ __('app.terms_required') }}
                                </p>
                            </div>
                        </div>

                        <p class="text-xs text-muted-foreground mb-4">
                            {{ __('app.must_accept_conditions') }}
                        </p>
                    </form>

                    <script>
                        function handleOverlayClick() {
                            const privacyChecked = document.getElementById('privacy').checked;
                            const rulesChecked = document.getElementById('rules').checked;

                            // Show errors on unchecked boxes
                            if (!privacyChecked) {
                                showError('privacy');
                            }
                            if (!rulesChecked) {
                                showError('rules');
                            }

                            // Scroll to first error
                            const firstError = !privacyChecked ? 'privacy' : 'rules';
                            document.getElementById(`${firstError}-label`).scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }

                        function updateTelegramOverlay() {
                            const privacyChecked = document.getElementById('privacy').checked;
                            const rulesChecked = document.getElementById('rules').checked;
                            const overlay = document.getElementById('telegram-overlay');

                            if (overlay) {
                                if (privacyChecked && rulesChecked) {
                                    // Hide overlay - allow interaction
                                    overlay.style.opacity = '0';
                                    overlay.style.pointerEvents = 'none';
                                } else {
                                    // Show overlay - block interaction
                                    overlay.style.opacity = '1';
                                    overlay.style.pointerEvents = 'all';
                                }
                            }
                        }

                        function handleCheckboxChange(checkbox, name) {
                            const form = checkbox.form;
                            const hiddenInput = form.querySelector(`input[name=${name}_accepted][type=hidden]`);
                            hiddenInput.value = checkbox.checked ? '1' : '0';

                            // Clear error state when checkbox is checked
                            if (checkbox.checked) {
                                clearError(name);
                            }

                            // Update Telegram overlay visibility
                            updateTelegramOverlay();
                        }

                        function clearError(name) {
                            const label = document.getElementById(`${name}-label`);
                            const error = document.getElementById(`${name}-error`);
                            const checkbox = document.getElementById(name);

                            label.classList.remove('bg-red-50', 'dark:bg-red-950/20', 'border', 'border-red-300', 'dark:border-red-800');
                            error.classList.add('hidden');
                            checkbox.classList.remove('border-red-500', 'dark:border-red-500');
                        }

                        function showError(name) {
                            const label = document.getElementById(`${name}-label`);
                            const error = document.getElementById(`${name}-error`);
                            const checkbox = document.getElementById(name);

                            label.classList.add('bg-red-50', 'dark:bg-red-950/20', 'border', 'border-red-300', 'dark:border-red-800');
                            error.classList.remove('hidden');
                            checkbox.classList.add('border-red-500', 'dark:border-red-500');
                        }

                        function validateForm() {
                            const privacyChecked = document.getElementById('privacy').checked;
                            const rulesChecked = document.getElementById('rules').checked;

                            let isValid = true;

                            // Clear all errors first
                            clearError('privacy');
                            clearError('rules');

                            // Validate privacy checkbox
                            if (!privacyChecked) {
                                showError('privacy');
                                isValid = false;
                            }

                            // Validate rules checkbox
                            if (!rulesChecked) {
                                showError('rules');
                                isValid = false;
                            }

                            // Scroll to first error if validation fails
                            if (!isValid) {
                                const firstError = !privacyChecked ? 'privacy' : 'rules';
                                document.getElementById(`${firstError}-label`).scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }

                            return isValid;
                        }

                        // Initialize overlay state on page load
                        document.addEventListener('DOMContentLoaded', function() {
                            updateTelegramOverlay();
                        });

                        // Re-initialize overlay after Livewire navigation
                        document.addEventListener('livewire:navigated', function() {
                            updateTelegramOverlay();
                        });
                    </script>

                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700 text-center">
                        <p class="text-xs text-muted-foreground">
                            {{ __('app.already_have_account') }}
                            <a href="{{ route('login') }}" wire:navigate class="text-primary hover:underline font-medium">{{ __('app.sign_in') }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
