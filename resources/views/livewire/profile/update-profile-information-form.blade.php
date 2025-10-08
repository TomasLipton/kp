<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
//            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="space-y-6">
    <header class="border-l-4 border-primary pl-4">
        <div class="flex items-center gap-3">
            @svg('lucide-user-circle', 'w-6 h-6 text-primary')
            <h2 class="text-2xl font-semibold bg-gradient-primary bg-clip-text text-transparent">
                {{ __('Profile Information') }}
            </h2>
        </div>
        <p class="mt-2 text-sm text-muted-foreground">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-8 space-y-6">
        <!-- Name Field -->
        <div class="group relative">
            <x-input-label for="name" class="flex items-center gap-2 text-sm font-medium mb-2">
                @svg('lucide-user', 'w-4 h-4 text-blue-500')
                {{ __('Name') }}
            </x-input-label>
            <div class="relative">
                <x-text-input
                    wire:model="name"
                    id="name"
                    name="name"
                    type="text"
                    class="block w-full pl-4 pr-4 py-3 border-2 border-gray-200 rounded-lg transition-all duration-200 focus:border-primary focus:ring-2 focus:ring-primary/20 hover:border-gray-300"
                    required
                    autofocus
                    autocomplete="name"
                />
            </div>
            <x-input-error class="mt-2 text-xs flex items-center gap-1" :messages="$errors->get('name')" />
        </div>

        <!-- Email Field -->
        <div class="group relative">
            <x-input-label for="email" class="flex items-center gap-2 text-sm font-medium mb-2">
                @svg('lucide-mail', 'w-4 h-4 text-gray-400')
                {{ __('Email') }}
                <span class="text-xs text-muted-foreground">({{ __('Read-only') }})</span>
            </x-input-label>
            <div class="relative">
                <x-text-input
                    wire:model="email"
                    id="email"
                    name="email"
                    type="email"
                    class="block w-full pl-4 pr-4 py-3 border-2 border-gray-100 bg-gray-50 rounded-lg cursor-not-allowed opacity-60"
                    disabled
                    autocomplete="username"
                />
            </div>
            <x-input-error class="mt-2 text-xs flex items-center gap-1" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div class="mt-3 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                    <div class="flex items-start gap-3">
                        @svg('lucide-alert-circle', 'w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5')
                        <div class="flex-1">
                            <p class="text-sm text-amber-800 font-medium">
                                {{ __('Your email address is unverified.') }}
                            </p>
                            <button
                                wire:click.prevent="sendVerification"
                                class="mt-2 inline-flex items-center gap-2 text-sm text-amber-700 hover:text-amber-900 font-medium underline decoration-2 underline-offset-2 transition-colors"
                            >
                                @svg('lucide-send', 'w-3 h-3')
                                {{ __('Click here to re-send the verification email.') }}
                            </button>

                            @if (session('status') === 'verification-link-sent')
                                <div class="mt-3 flex items-center gap-2 text-sm text-green-700 font-medium">
                                    @svg('lucide-check-circle', 'w-4 h-4')
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
            <x-primary-button class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-primary hover:shadow-glow transition-all duration-300 rounded-lg font-medium">
                @svg('lucide-save', 'w-4 h-4')
                {{ __('Save') }}
                <span wire:loading wire:target="updateProfileInformation" class="ml-1">
                    @svg('lucide-loader-2', 'w-4 h-4 animate-spin')
                </span>
            </x-primary-button>

            <x-action-message class="text-sm font-medium" on="profile-updated">
                <span class="inline-flex items-center gap-2 text-green-600 animate-fade-in">
                    @svg('lucide-check-circle', 'w-4 h-4')
                    {{ __('Saved.') }}
                </span>
            </x-action-message>
        </div>
    </form>
</section>
