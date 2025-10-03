<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="max-w-2xl w-full">
        <div class="text-center mb-12">
            <div class="relative inline-block mb-8">
                <div class="absolute inset-0 bg-primary/20 rounded-full blur-3xl"></div>
                <div class="relative bg-gradient-to-br from-primary/10 to-primary/5 rounded-full p-12 border border-primary/20">
                    @svg('lucide-construction', 'w-32 h-32 text-primary')
                </div>
            </div>

            <h1 class="text-4xl md:text-5xl font-bold bg-gradient-primary bg-clip-text text-transparent mb-4">
                {{ __('under_construction.title') }}
            </h1>
            <p class="text-lg text-muted-foreground mb-8">
                {{ __('under_construction.subtitle') }}
            </p>
        </div>

        <div class="bg-card/80 backdrop-blur-sm rounded-2xl p-8 border border-border/50 shadow-xl">
            <div class="space-y-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 p-2 bg-primary/10 rounded-lg">
                        @svg('lucide-sparkles', 'w-6 h-6 text-primary')
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg mb-1">{{ __('under_construction.feature1_title') }}</h3>
                        <p class="text-sm text-muted-foreground">{{ __('under_construction.feature1_desc') }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 p-2 bg-primary/10 rounded-lg">
                        @svg('lucide-rocket', 'w-6 h-6 text-primary')
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg mb-1">{{ __('under_construction.feature2_title') }}</h3>
                        <p class="text-sm text-muted-foreground">{{ __('under_construction.feature2_desc') }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 p-2 bg-primary/10 rounded-lg">
                        @svg('lucide-zap', 'w-6 h-6 text-primary')
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg mb-1">{{ __('under_construction.feature3_title') }}</h3>
                        <p class="text-sm text-muted-foreground">{{ __('under_construction.feature3_desc') }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 p-4 bg-primary/5 rounded-lg border border-primary/20">
                <p class="text-sm text-center text-muted-foreground">
                    {{ __('under_construction.coming_soon') }}
                </p>
            </div>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('dashboard') }}" wire:navigate class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-primary text-primary-foreground rounded-lg font-semibold hover:scale-105 transition-all">
                @svg('lucide-arrow-left', 'w-5 h-5')
                {{ __('under_construction.back_home') }}
            </a>
        </div>
    </div>
</div>
