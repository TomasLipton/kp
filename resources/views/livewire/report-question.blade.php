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

            {{-- Form --}}
            <div class="mb-4">
                <label for="message" class="block text-sm font-medium text-foreground mb-2">
                    Opisz problem
                </label>
                <textarea
                    wire:model="message"
                    id="message"
                    rows="4"
                    class="w-full px-3 py-2 border border-border rounded-lg focus:ring-2 focus:ring-primary focus:border-primary resize-none"
                    placeholder="Np. błąd w pytaniu, niepoprawna odpowiedź, niejasna treść..."
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
