@php
    $profile = \App\Models\Profile::current();
    $projectsCount = \Illuminate\Support\Facades\Schema::hasTable('projects')
        ? \Illuminate\Support\Facades\DB::table('projects')->count()
        : 0;
    $experiencesCount = \Illuminate\Support\Facades\Schema::hasTable('experiences')
        ? \Illuminate\Support\Facades\DB::table('experiences')->count()
        : 0;
    $skillsCount = \Illuminate\Support\Facades\Schema::hasTable('skills')
        ? \Illuminate\Support\Facades\DB::table('skills')->count()
        : 0;
    $unreadMessagesCount = \Illuminate\Support\Facades\Schema::hasTable('messages')
        ? \Illuminate\Support\Facades\DB::table('messages')->where('is_read', false)->count()
        : 0;
@endphp

<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <flux:heading size="xl">{{ __('Portfolio Overview') }}</flux:heading>
                <flux:text class="mt-1">
                    {{ __('Monitor your portfolio metrics and manage your public profile.') }}
                </flux:text>
            </div>

            <div class="flex items-center gap-3">
                <flux:badge :color="$profile->is_available ? 'green' : 'zinc'" size="lg">
                    {{ $profile->is_available ? __('Available for Work') : __('Currently Unavailable') }}
                </flux:badge>

                <flux:button variant="primary" :href="route('admin.profile')" wire:navigate>
                    {{ __('Edit Profile & Hero') }}
                </flux:button>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:text class="text-sm font-medium">{{ __('Total Projects') }}</flux:text>
                <div class="mt-2 text-3xl font-semibold text-zinc-900 dark:text-zinc-100">
                    {{ $projectsCount }}
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:text class="text-sm font-medium">{{ __('Experiences') }}</flux:text>
                <div class="mt-2 text-3xl font-semibold text-zinc-900 dark:text-zinc-100">
                    {{ $experiencesCount }}
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:text class="text-sm font-medium">{{ __('Skills') }}</flux:text>
                <div class="mt-2 text-3xl font-semibold text-zinc-900 dark:text-zinc-100">
                    {{ $skillsCount }}
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:text class="text-sm font-medium">{{ __('Unread Messages') }}</flux:text>
                <div class="mt-2 text-3xl font-semibold text-zinc-900 dark:text-zinc-100">
                    {{ $unreadMessagesCount }}
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex items-start gap-4">
                    @if ($profile->avatarUrl())
                        <img
                            src="{{ $profile->avatarUrl() }}"
                            alt="{{ $profile->name }}"
                            class="size-14 shrink-0 rounded-full border border-zinc-200 object-cover dark:border-zinc-700"
                        />
                    @endif

                    <div class="space-y-2">
                        <flux:heading size="lg">{{ $profile->headline }}</flux:heading>
                        <flux:text class="font-medium text-zinc-700 dark:text-zinc-300">
                            {{ $profile->tagline }}
                        </flux:text>
                        @if ($profile->bio)
                            <flux:text class="max-w-3xl text-sm">
                                {{ $profile->bio }}
                            </flux:text>
                        @endif
                        <flux:text class="text-sm">
                            {{ __('Location:') }} {{ $profile->location }}
                        </flux:text>
                    </div>
                </div>

                <flux:button variant="outline" :href="route('home')" target="_blank">
                    {{ __('View Public Portfolio') }}
                </flux:button>
            </div>
        </div>
    </div>
</x-layouts::app>
