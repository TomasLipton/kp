<div>

    <div class="py-12">
        <div class=" mx-auto  space-y-6">

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.update-profile-information-form/>
                </div>
            </div>

            <livewire:profile.manage-subscription/>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl space-y-6">
                    <header class="border-l-4 border-primary pl-4">
                        <div class="flex items-center gap-3">
                            @svg('lucide-link-2', 'w-6 h-6 text-primary')
                            <h2 class="text-2xl font-semibold bg-gradient-primary bg-clip-text text-transparent">
                                {{ __('Konta społecznościowe') }}
                            </h2>
                        </div>
                        <p class="mt-2 text-sm text-muted-foreground">
                            {{ __('Połączone konta z zewnętrznych dostawców.') }}
                        </p>
                    </header>

                    @if($socialiteUsers->count() > 0)
                        <div class="flex flex-wrap gap-3 pt-2">
                            @foreach($socialiteUsers as $socialite)
                                @php
                                    $providerColors = [
                                        'google' => 'bg-red-50 text-red-700 border-red-200',
                                        'facebook' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'github' => 'bg-gray-50 text-gray-700 border-gray-200',
                                        'twitter' => 'bg-sky-50 text-sky-700 border-sky-200',
                                    ];
                                    $providerIcons = [
                                        'google' => 'lucide-chrome',
                                        'facebook' => 'lucide-facebook',
                                        'github' => 'lucide-github',
                                        'twitter' => 'lucide-twitter',
                                    ];
                                    $colorClass = $providerColors[$socialite->provider] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                    $icon = $providerIcons[$socialite->provider] ?? 'lucide-link';
                                @endphp
                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-sm border-2 {{ $colorClass }} transition-all duration-200 hover:shadow-md">
                                    @svg($icon, 'w-4 h-4')
                                    {{ ucfirst($socialite->provider) }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <div class="flex items-center gap-3 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            @svg('lucide-info', 'w-5 h-5 text-gray-400 flex-shrink-0')
                            <p class="text-sm text-gray-600">
                                {{ __('Nie masz połączonych kont społecznościowych.') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.delete-user-form/>
                </div>
            </div>
        </div>
    </div>
</div>
