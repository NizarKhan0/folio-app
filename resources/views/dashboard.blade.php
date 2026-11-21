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
    <div class="relative mx-auto flex h-full w-full max-w-6xl flex-1 flex-col gap-6">
        <div class="flex flex-col gap-4 border-b border-cyan-600/20 pb-5 sm:flex-row sm:items-center sm:justify-between dark:border-cyan-400/20">
            <div>
                <div class="font-mono text-xs font-bold uppercase tracking-widest text-cyan-700 dark:text-cyan-400">
                    00 // {{ __('SYS.ADMIN_WORKSPACE') }}
                </div>
                <flux:heading size="xl" class="mt-1">{{ __('Portfolio Overview') }}</flux:heading>
                <flux:text class="mt-1">
                    {{ __('Monitor your portfolio metrics and manage your public profile.') }}
                </flux:text>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <flux:badge :color="$profile->is_available ? 'cyan' : 'zinc'" size="lg">
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
                class="group relative overflow-hidden border border-cyan-600/25 bg-white/95 p-5 shadow-xs transition hover:-translate-y-0.5 hover:border-cyan-500 dark:border-cyan-400/30 dark:bg-[#0A0D16]/90 dark:hover:border-cyan-400 dark:hover:shadow-[0_0_24px_rgba(34,211,238,0.14)]"
            >
                <div class="pointer-events-none absolute top-0 right-0 left-0 h-0.5 bg-gradient-to-r from-cyan-400 to-sky-500"></div>
                <div class="flex items-center justify-between">
                    <flux:text class="font-mono text-xs font-bold uppercase tracking-wider text-cyan-700 dark:text-cyan-300">{{ __('Total Projects') }}</flux:text>
                    <div class="flex size-9 items-center justify-center border border-cyan-600/30 bg-cyan-500/10 text-cyan-700 dark:border-cyan-400/35 dark:bg-cyan-400/10 dark:text-cyan-300">
                        <flux:icon name="folder-git-2" class="size-4" />
                    </div>
                </div>
                <div class="mt-3 font-mono text-3xl font-black tracking-tight text-slate-900 tabular-nums dark:text-white">
                    {{ $projectsCount }}
                </div>
                <div class="mt-1 font-mono text-xs text-slate-500 group-hover:text-cyan-700 dark:text-zinc-400 dark:group-hover:text-cyan-300">
                    {{ __('Manage showcase →') }}
                </div>
            </a>

            <a
                href="{{ route('admin.experience-skills') }}"
                wire:navigate
                class="group relative overflow-hidden border border-fuchsia-600/25 bg-white/95 p-5 shadow-xs transition hover:-translate-y-0.5 hover:border-fuchsia-500 dark:border-fuchsia-500/30 dark:bg-[#0A0D16]/90 dark:hover:border-fuchsia-400 dark:hover:shadow-[0_0_24px_rgba(217,70,239,0.14)]"
            >
                <div class="pointer-events-none absolute top-0 right-0 left-0 h-0.5 bg-gradient-to-r from-fuchsia-500 to-pink-500"></div>
                <div class="flex items-center justify-between">
                    <flux:text class="font-mono text-xs font-bold uppercase tracking-wider text-fuchsia-700 dark:text-fuchsia-300">{{ __('Experiences') }}</flux:text>
                    <div class="flex size-9 items-center justify-center border border-fuchsia-600/30 bg-fuchsia-500/10 text-fuchsia-700 dark:border-fuchsia-500/35 dark:bg-fuchsia-500/10 dark:text-fuchsia-300">
                        <flux:icon name="book-open-text" class="size-4" />
                    </div>
                </div>
                <div class="mt-3 font-mono text-3xl font-black tracking-tight text-slate-900 tabular-nums dark:text-white">
                    {{ $experiencesCount }}
                </div>
                <div class="mt-1 font-mono text-xs text-slate-500 group-hover:text-fuchsia-700 dark:text-zinc-400 dark:group-hover:text-fuchsia-300">
                    {{ __('Career timeline →') }}
                </div>
            </a>

            <a
                href="{{ route('admin.experience-skills') }}"
                wire:navigate
                class="group relative overflow-hidden border border-amber-500/30 bg-white/95 p-5 shadow-xs transition hover:-translate-y-0.5 hover:border-amber-500 dark:border-yellow-400/30 dark:bg-[#0A0D16]/90 dark:hover:border-yellow-300 dark:hover:shadow-[0_0_24px_rgba(250,204,21,0.14)]"
            >
                <div class="pointer-events-none absolute top-0 right-0 left-0 h-0.5 bg-gradient-to-r from-amber-400 to-orange-500 dark:from-yellow-300 dark:to-amber-500"></div>
                <div class="flex items-center justify-between">
                    <flux:text class="font-mono text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-yellow-300">{{ __('Skills') }}</flux:text>
                    <div class="flex size-9 items-center justify-center border border-amber-500/35 bg-amber-500/10 text-amber-700 dark:border-yellow-400/35 dark:bg-yellow-400/10 dark:text-yellow-300">
                        <flux:icon name="sparkles" class="size-4" />
                    </div>
                </div>
                <div class="mt-3 font-mono text-3xl font-black tracking-tight text-slate-900 tabular-nums dark:text-white">
                    {{ $skillsCount }}
                </div>
                <div class="mt-1 font-mono text-xs text-slate-500 group-hover:text-amber-700 dark:text-zinc-400 dark:group-hover:text-yellow-300">
                    {{ __('Tech stack catalog →') }}
                </div>
            </a>

            <a
                href="{{ route('admin.messages') }}"
                wire:navigate
                class="group relative overflow-hidden border border-emerald-600/25 bg-white/95 p-5 shadow-xs transition hover:-translate-y-0.5 hover:border-emerald-500 dark:border-emerald-400/30 dark:bg-[#0A0D16]/90 dark:hover:border-emerald-400 dark:hover:shadow-[0_0_24px_rgba(16,185,129,0.14)]"
            >
                <div class="pointer-events-none absolute top-0 right-0 left-0 h-0.5 bg-gradient-to-r from-emerald-400 to-teal-500"></div>
                <div class="flex items-center justify-between">
                    <flux:text class="font-mono text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-300">{{ __('Unread Messages') }}</flux:text>
                    <div class="flex size-9 items-center justify-center border border-emerald-600/30 bg-emerald-500/10 text-emerald-700 dark:border-emerald-400/35 dark:bg-emerald-400/10 dark:text-emerald-300">
                        <flux:icon name="inbox" class="size-4" />
                    </div>
                </div>
                <div class="mt-3 font-mono text-3xl font-black tracking-tight text-slate-900 tabular-nums dark:text-white">
                    {{ $unreadMessagesCount }}
                </div>
                <div class="mt-1 font-mono text-xs text-slate-500 group-hover:text-emerald-700 dark:text-zinc-400 dark:group-hover:text-emerald-300">
                    {{ __('Open inbox →') }}
                </div>
            </a>
        </div>

        <div class="relative overflow-hidden border border-cyan-600/30 bg-white/95 shadow-sm dark:border-cyan-400/35 dark:bg-[#0A0D16]/95 dark:shadow-[0_0_35px_rgba(34,211,238,0.1)]">
            <div class="h-1 w-full bg-gradient-to-r from-cyan-400 via-fuchsia-500 to-yellow-300"></div>
            @if ($profile->bannerUrl())
                <div class="relative h-28 w-full overflow-hidden border-b border-slate-200 dark:border-white/10">
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
                            class="size-16 shrink-0 border-2 border-cyan-500 object-cover shadow-xs dark:border-cyan-400"
                        />
                    @else
                        <div class="flex size-16 shrink-0 items-center justify-center border-2 border-cyan-500 bg-cyan-500/10 font-mono text-lg font-black text-cyan-700 dark:border-cyan-400 dark:bg-cyan-400/10 dark:text-cyan-300">
                            {{ \Illuminate\Support\Str::initials($profile->name ?: 'NK', true) }}
                        </div>
                    @endif

                    <div class="space-y-2">
                        <div class="flex flex-wrap items-center gap-2">
                            <flux:heading size="lg">{{ $profile->headline }}</flux:heading>
                            <flux:badge size="sm" color="zinc">{{ $profile->location }}</flux:badge>
                        </div>

                        <flux:text class="font-mono text-sm font-medium text-cyan-700 dark:text-cyan-300">
                            &gt;_ {{ $profile->tagline }}
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
