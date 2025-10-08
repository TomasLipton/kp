<div>

    <div class="py-12">
        <div class=" mx-auto  space-y-6">

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.update-profile-information-form/>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h2 class="text-lg font-semibold mb-4">Konta społecznościowe</h2>

                    @foreach($socialiteUsers as $socialite)
                        <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-gray-500/10 ring-inset">{{ucfirst($socialite->provider)}}</span>
                    @endforeach
                </div>
            </div>

{{--            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">--}}
{{--                <div class="max-w-xl">--}}
{{--                    <livewire:profile.update-password-form/>--}}
{{--                </div>--}}
{{--            </div>--}}

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.delete-user-form/>
                </div>
            </div>
        </div>
    </div>
</div>
