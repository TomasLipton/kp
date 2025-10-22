<div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
    <div class="max-w-xl space-y-6">
        <header class="border-l-4 border-primary pl-4">
            <div class="flex items-center gap-3">
                @svg('lucide-credit-card', 'w-6 h-6 text-primary')
                <h2 class="text-2xl font-semibold bg-gradient-primary bg-clip-text text-transparent">
                    {{ __('Subskrypcja') }}
                </h2>
            </div>
            <p class="mt-2 text-sm text-muted-foreground">
                {{ __('Zarządzaj swoją subskrypcją i planem.') }}
            </p>
        </header>

        @if($subscription && $subscription->active())
            <div class="space-y-4">
                <div class="p-4 bg-gradient-to-br from-primary/10 to-primary/5 border-2 border-primary/20 rounded-lg">
                    <div class="flex items-start justify-between">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                @svg('lucide-check-circle', 'w-5 h-5 text-green-600')
                                <span class="font-semibold text-lg text-gray-900">{{ __('Aktywna subskrypcja') }}</span>
                            </div>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p>
                                    <span class="font-medium">{{ __('Plan:') }}</span>
                                    <span class="capitalize">{{ $subscription->type ?? 'Standard' }}</span>
                                </p>
                                <p>
                                    <span class="font-medium">{{ __('Status:') }}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        {{ ucfirst($subscription->stripe_status) }}
                                    </span>
                                </p>
                                @if($subscription->trial_ends_at && $subscription->onTrial())
                                    <p>
                                        <span class="font-medium">{{ __('Okres próbny kończy się:') }}</span>
                                        {{ $subscription->trial_ends_at->format('d.m.Y') }}
                                    </p>
                                @endif
                                @if($subscription->ends_at)
                                    <p>
                                        <span class="font-medium">{{ __('Wygasa:') }}</span>
                                        {{ $subscription->ends_at->format('d.m.Y') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        @svg('lucide-crown', 'w-12 h-12 text-primary/30')
                    </div>
                </div>

                @if($subscription->onGracePeriod())
                    <div class="flex items-center gap-3 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                        @svg('lucide-alert-circle', 'w-5 h-5 text-amber-600 flex-shrink-0')
                        <div class="text-sm">
                            <p class="font-medium text-amber-900">{{ __('Subskrypcja została anulowana') }}</p>
                            <p class="text-amber-700">{{ __('Masz dostęp do funkcji premium do:') }} {{ $subscription->ends_at->format('d.m.Y') }}</p>
                        </div>
                    </div>
                @endif

                {{-- Payment Details --}}
                <div class="p-4 bg-white border border-gray-200 rounded-lg">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            @svg('lucide-credit-card', 'w-5 h-5 text-gray-600')
                            <h3 class="font-semibold text-gray-900">{{ __('Metoda płatności') }}</h3>
                        </div>
                        <a href="{{ auth()->user()->billingPortalUrl() }}" target="_blank" class="text-sm text-primary hover:text-primary/80 font-medium transition-colors duration-200">
                            {{ __('Zmień') }}
                        </a>
                    </div>
                    @if($paymentMethod)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            @svg('lucide-credit-card', 'w-6 h-6 text-gray-400')
                            <div class="text-sm">
                                <p class="font-medium text-gray-900">
                                    {{ ucfirst($paymentMethod->card->brand) }} •••• {{ $paymentMethod->card->last4 }}
                                </p>
                                <p class="text-gray-600">
                                    {{ __('Wygasa') }} {{ $paymentMethod->card->exp_month }}/{{ $paymentMethod->card->exp_year }}
                                </p>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-600">{{ __('Brak zapisanej metody płatności') }}</p>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="p-4 bg-white border border-gray-200 rounded-lg space-y-3">
                    <div class="flex items-center gap-2 mb-3">
                        @svg('lucide-settings', 'w-5 h-5 text-gray-600')
                        <h3 class="font-semibold text-gray-900">{{ __('Zarządzanie') }}</h3>
                    </div>

                    {{-- Invoices --}}
                    @if($this->invoices && count($this->invoices) > 0)
                        <div class="space-y-2">
                            <h4 class="text-sm font-medium text-gray-700">{{ __('Ostatnie faktury') }}</h4>
                            <div class="space-y-1">
                                @foreach($this->invoices as $invoice)
                                    <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded transition-colors duration-200">
                                        <div class="flex items-center gap-2 text-sm">
                                            @svg('lucide-file-text', 'w-4 h-4 text-gray-400')
                                            <span class="text-gray-700">{{ $invoice->date()->format('d.m.Y') }}</span>
                                            <span class="text-gray-900 font-medium">{{ $invoice->total() }}</span>
                                        </div>
                                        <a href="{{ $invoice->asStripeInvoice()->invoice_pdf }}" target="_blank" class="text-xs text-primary hover:text-primary/80 font-medium transition-colors duration-200">
                                            {{ __('Pobierz') }}
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="border-t border-gray-200 pt-3"></div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="space-y-2">
                        <a href="{{ auth()->user()->billingPortalUrl() }}" target="_blank" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200 group">
                            <div class="flex items-center gap-2">
                                @svg('lucide-external-link', 'w-4 h-4 text-gray-600')
                                <span class="text-sm font-medium text-gray-900">{{ __('Portal rozliczeniowy Stripe') }}</span>
                            </div>
                            @svg('lucide-chevron-right', 'w-4 h-4 text-gray-400 group-hover:text-gray-600 transition-colors duration-200')
                        </a>

                        @if($subscription->onGracePeriod())
                            <button wire:click="resumeSubscription" class="w-full flex items-center justify-center gap-2 p-3 bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg transition-colors duration-200">
                                @svg('lucide-rotate-ccw', 'w-4 h-4 text-green-700')
                                <span class="text-sm font-medium text-green-700">{{ __('Wznów subskrypcję') }}</span>
                            </button>
                        @else
                            <button wire:click="cancelSubscription" class="w-full flex items-center justify-center gap-2 p-3 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition-colors duration-200">
                                @svg('lucide-x-circle', 'w-4 h-4 text-red-700')
                                <span class="text-sm font-medium text-red-700">{{ __('Anuluj subskrypcję') }}</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="space-y-4">
                <div class="p-6 bg-gradient-to-br from-primary/5 to-primary/10 border-2 border-dashed border-primary/30 rounded-lg text-center">
                    @svg('lucide-sparkles', 'w-12 h-12 text-primary mx-auto mb-3')
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        {{ __('Odblokuj pełny potencjał!') }}
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        {{ __('Uzyskaj dostęp do premium funkcji i nieograniczonych możliwości.') }}
                    </p>
                    <a href="#" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-medium rounded-lg hover:bg-primary/90 transition-colors duration-200">
                        @svg('lucide-zap', 'w-4 h-4')
                        {{ __('Rozpocznij subskrypcję') }}
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-center">
                    <div class="p-3 bg-white border border-gray-200 rounded-lg">
                        @svg('lucide-infinity', 'w-6 h-6 text-primary mx-auto mb-2')
                        <p class="text-xs text-gray-600">{{ __('Nielimitowane quizy') }}</p>
                    </div>
                    <div class="p-3 bg-white border border-gray-200 rounded-lg">
                        @svg('lucide-brain', 'w-6 h-6 text-primary mx-auto mb-2')
                        <p class="text-xs text-gray-600">{{ __('AI Asystent') }}</p>
                    </div>
                    <div class="p-3 bg-white border border-gray-200 rounded-lg">
                        @svg('lucide-trophy', 'w-6 h-6 text-primary mx-auto mb-2')
                        <p class="text-xs text-gray-600">{{ __('Szczegółowe statystyki') }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
