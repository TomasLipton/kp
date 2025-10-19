<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app-kp')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>


<script>
    // Handle provider button clicks
    function handleProviderSubmit(event) {
        event.preventDefault();
        const provider = event.target.value;
        const form = document.getElementById('socialLoginForm');

        // Set the form action based on provider
        if (provider === 'google') {
            form.action = "{{ route('auth.google.redirect') }}";
        }

        // Validate form before submitting
        if (validateForm()) {
            form.submit();
        }
    }

    // Initialize event listeners
    function initializeEventListeners() {
        // Add click handlers to provider buttons
        const providerButtons = document.querySelectorAll('button[name="provider"]');
        providerButtons.forEach(button => {
            button.addEventListener('click', handleProviderSubmit);
        });
    }

    // Initialize after page load
    document.addEventListener('DOMContentLoaded', initializeEventListeners);

    // Re-initialize after Livewire navigation
    document.addEventListener('livewire:navigated', initializeEventListeners);
</script>
{{--<script async src="https://telegram.org/js/telegram-widget.js?22" data-telegram-login="quiz_polaka_bot" data-size="large" data-radius="12" data-auth-url="https://kp.test/auth/callback/telegram"></script>--}}
    <div class="min-h-[calc(100vh-4rem)] flex items-center justify-center py-8 px-4">

        <main class="w-full max-w-md">
            <div class="p-6 bg-gradient-card shadow-lg border border-gray-200 dark:border-gray-800 rounded-xl">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold mb-2">{{ __('app.login_to_account') }}</h2>
                    <p class="text-sm text-muted-foreground">{{ __('app.choose_login_method') }}</p>
                </div>

                    <form method="GET" id="socialLoginForm">
                        @csrf

                        <!-- Hidden fields for tracking checkbox states -->
                        <input type="hidden" name="privacy_accepted" value="{{ old('privacy_accepted', '0') }}">
                        <input type="hidden" name="rules_accepted" value="{{ old('rules_accepted', '0') }}">

                        <!-- Social login buttons -->
                        <button
                            type="button"
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
                            {{ __('app.login_with_google') }}
                        </button>

                        {{-- Telegram Login Widget --}}
                        <div class="relative w-full py-2 px-3 rounded-lg flex items-center justify-center transition-all mb-4">
                            <script async src="https://telegram.org/js/telegram-widget.js?22" data-telegram-login="quiz_polaka_bot" data-size="large" data-radius="12" data-auth-url="https://kp.test/auth/callback/telegram"></script>

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

                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-xs text-muted-foreground text-center">
                            {{ __('app.dont_have_account') }}
                            <a href="{{ route('register') }}" wire:navigate class="text-primary hover:underline font-medium">{{ __('app.sign_up') }}</a>
                        </p>
                    </div>
                </div>
        </main>
    </div>
{{--</div>--}}

