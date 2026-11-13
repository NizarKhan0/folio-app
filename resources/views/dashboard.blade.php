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
    <div class="mx-auto flex h-full w-full max-w-6xl flex-1 flex-col gap-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">
                    {{ __('Admin Workspace') }}
                </div>
                <flux:heading size="xl" class="mt-0.5">{{ __('Portfolio Overview') }}</flux:heading>
                <flux:text class="mt-1">
                    {{ __('Monitor your portfolio metrics and manage your public profile.') }}
                </flux:text>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <flux:badge :color="$profile->is_available ? 'green' : 'zinc'" size="lg">
                    {{ $profile->is_available ? __('Available for Work') : __('Currently Unavailable') }}
                </flux:badge>

                <flux:button variant="outline" :href="route('home')" target="_blank">
                    {{ __('View Public Portfolio') }} ↗
                </flux:button>

                <flux:button variant="primary" icon="pencil-square" :href="route('admin.profile')" wire:navigate>
                    {{ __('Edit Profile & Hero') }}
                </flux:button>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <a
                href="{{ route('admin.projects') }}"
                wire:navigate
                class="group rounded-2xl border border-zinc-200 bg-white p-5 shadow-xs transition hover:border-zinc-300 hover:shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900 dark:hover:border-zinc-600"
            >
                <div class="flex items-center justify-between">
                    <flux:text class="text-xs font-semibold uppercase tracking-wider">{{ __('Total Projects') }}</flux:text>
                    <div class="flex size-9 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                        <flux:icon name="folder-git-2" class="size-4" />
                    </div>
                </div>
                <div class="mt-3 text-3xl font-bold tracking-tight text-zinc-900 tabular-nums dark:text-zinc-100">
                    {{ $projectsCount }}
                </div>
                <div class="mt-1 text-xs text-zinc-500 group-hover:text-zinc-700 dark:text-zinc-400 dark:group-hover:text-zinc-300">
                    {{ __('Manage showcase →') }}
                </div>
            </a>

            <a
                href="{{ route('admin.experience-skills') }}"
                wire:navigate
                class="group rounded-2xl border border-zinc-200 bg-white p-5 shadow-xs transition hover:border-zinc-300 hover:shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900 dark:hover:border-zinc-600"
            >
                <div class="flex items-center justify-between">
                    <flux:text class="text-xs font-semibold uppercase tracking-wider">{{ __('Experiences') }}</flux:text>
                    <div class="flex size-9 items-center justify-center rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400">
                        <flux:icon name="book-open-text" class="size-4" />
                    </div>
                </div>
                <div class="mt-3 text-3xl font-bold tracking-tight text-zinc-900 tabular-nums dark:text-zinc-100">
                    {{ $experiencesCount }}
                </div>
                <div class="mt-1 text-xs text-zinc-500 group-hover:text-zinc-700 dark:text-zinc-400 dark:group-hover:text-zinc-300">
                    {{ __('Career timeline →') }}
                </div>
            </a>

            <a
                href="{{ route('admin.experience-skills') }}"
                wire:navigate
                class="group rounded-2xl border border-zinc-200 bg-white p-5 shadow-xs transition hover:border-zinc-300 hover:shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900 dark:hover:border-zinc-600"
            >
                <div class="flex items-center justify-between">
                    <flux:text class="text-xs font-semibold uppercase tracking-wider">{{ __('Skills') }}</flux:text>
                    <div class="flex size-9 items-center justify-center rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400">
                        <flux:icon name="sparkles" class="size-4" />
                    </div>
                </div>
                <div class="mt-3 text-3xl font-bold tracking-tight text-zinc-900 tabular-nums dark:text-zinc-100">
                    {{ $skillsCount }}
                </div>
                <div class="mt-1 text-xs text-zinc-500 group-hover:text-zinc-700 dark:text-zinc-400 dark:group-hover:text-zinc-300">
                    {{ __('Tech stack catalog →') }}
                </div>
            </a>

            <a
                href="{{ route('admin.messages') }}"
                wire:navigate
                class="group rounded-2xl border border-zinc-200 bg-white p-5 shadow-xs transition hover:border-zinc-300 hover:shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900 dark:hover:border-zinc-600"
            >
                <div class="flex items-center justify-between">
                    <flux:text class="text-xs font-semibold uppercase tracking-wider">{{ __('Unread Messages') }}</flux:text>
                    <div class="flex size-9 items-center justify-center rounded-xl bg-rose-500/10 text-rose-600 dark:text-rose-400">
                        <flux:icon name="inbox" class="size-4" />
                    </div>
                </div>
                <div class="mt-3 text-3xl font-bold tracking-tight text-zinc-900 tabular-nums dark:text-zinc-100">
                    {{ $unreadMessagesCount }}
                </div>
                <div class="mt-1 text-xs text-zinc-500 group-hover:text-zinc-700 dark:text-zinc-400 dark:group-hover:text-zinc-300">
                    {{ __('Open inbox →') }}
                </div>
            </a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-xs dark:border-zinc-700/80 dark:bg-zinc-900">
            @if ($profile->bannerUrl())
                <div class="relative h-28 w-full overflow-hidden border-b border-zinc-200 dark:border-zinc-800">
                    <img
                        src="{{ $profile->bannerUrl() }}"
                        alt="{{ $profile->name }}"
                        class="h-full w-full object-cover opacity-70"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/70 to-transparent"></div>
                </div>
            @endif

            <div class="flex flex-col gap-6 p-6 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex items-start gap-4">
                    @if ($profile->avatarUrl())
                        <img
                            src="{{ $profile->avatarUrl() }}"
                            alt="{{ $profile->name }}"
                            class="size-16 shrink-0 rounded-2xl border border-zinc-200 object-cover shadow-xs dark:border-zinc-700"
                        />
                    @else
                        <div class="flex size-16 shrink-0 items-center justify-center rounded-2xl bg-zinc-900 text-lg font-bold text-white dark:bg-zinc-100 dark:text-zinc-900">
                            {{ \Illuminate\Support\Str::initials($profile->name ?: 'NK', true) }}
                        </div>
                    @endif

                    <div class="space-y-2">
                        <div class="flex flex-wrap items-center gap-2">
                            <flux:heading size="lg">{{ $profile->headline }}</flux:heading>
                            <flux:badge size="sm" color="zinc">{{ $profile->location }}</flux:badge>
                        </div>

                        <flux:text class="font-medium text-zinc-700 dark:text-zinc-300">
                            {{ $profile->tagline }}
                        </flux:text>

                        @if ($profile->bio)
                            <flux:text class="max-w-3xl text-sm leading-relaxed">
                                {{ $profile->bio }}
                            </flux:text>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
