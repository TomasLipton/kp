<div>
    {{-- Modal --}}
    <x-modal name="report-question" :show="$errors->isNotEmpty()" maxWidth="md" focusable>
        <form wire:submit="submit" class="p-6">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-foreground">
                    Zgłoś problem z pytaniem
                </h3>
                <button
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="text-muted-foreground hover:text-foreground transition-colors"
                >
                    @svg('lucide-x', 'w-5 h-5')
                </button>
            </div>

            {{-- Quick reasons --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-foreground mb-2">
                    Wybierz gotową przyczynę lub opisz własną
                </label>
                <div class="flex flex-wrap gap-2 mb-3">
                    <button
                        type="button"
                        wire:click="$set('message', 'Błąd w pytaniu')"
                        class="px-3 py-1.5 text-xs font-medium bg-orange-50 hover:bg-orange-100 text-orange-700 border border-orange-200 rounded-lg transition-colors"
                    >
                        Błąd w pytaniu
                    </button>
                    <button
                        type="button"
                        wire:click="$set('message', 'Niepoprawna odpowiedź')"
                        class="px-3 py-1.5 text-xs font-medium bg-orange-50 hover:bg-orange-100 text-orange-700 border border-orange-200 rounded-lg transition-colors"
                    >
                        Niepoprawna odpowiedź
                    </button>
                    <button
                        type="button"
                        wire:click="$set('message', 'Niejasna treść pytania')"
                        class="px-3 py-1.5 text-xs font-medium bg-orange-50 hover:bg-orange-100 text-orange-700 border border-orange-200 rounded-lg transition-colors"
                    >
                        Niejasna treść
                    </button>
                    <button
                        type="button"
                        wire:click="$set('message', 'Błąd ortograficzny lub gramatyczny')"
                        class="px-3 py-1.5 text-xs font-medium bg-orange-50 hover:bg-orange-100 text-orange-700 border border-orange-200 rounded-lg transition-colors"
                    >
                        Błąd ortograficzny
                    </button>
                    <button
                        type="button"
                        wire:click="$set('message', 'Nieaktualne informacje')"
                        class="px-3 py-1.5 text-xs font-medium bg-orange-50 hover:bg-orange-100 text-orange-700 border border-orange-200 rounded-lg transition-colors"
                    >
                        Nieaktualne info
                    </button>
                </div>
            </div>

            {{-- Form --}}
            <div class="mb-4">
                <label for="message" class="block text-sm font-medium text-foreground mb-2">
                    Szczegóły problemu
                </label>
                <textarea
                    wire:model="message"
                    id="message"
                    rows="4"
                    class="w-full px-3 py-2 border border-border rounded-lg focus:ring-2 focus:ring-primary focus:border-primary resize-none"
                    placeholder="Możesz dodać więcej szczegółów..."
                ></textarea>
                @error('message')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3">
                <button
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="px-4 py-2 text-sm font-medium text-foreground bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
                >
                    Anuluj
                </button>
                <button
                    type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 rounded-lg transition-colors"
                >
                    Wyślij zgłoszenie
                </button>
            </div>
        </form>
    </x-modal>
</div>
