<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <meta name="robots" content="noindex, nofollow" />
    </head>
    <body
        class="relative min-h-screen bg-slate-50 text-slate-900 antialiased selection:bg-cyan-400 selection:text-zinc-950 dark:bg-[#06070B] dark:text-zinc-100"
        style="--color-accent: #06b6d4; --color-accent-content: #0891b2; --color-accent-foreground: #09090b;"
    >
        {{-- Subtle Cyberpunk Grid & Ambient Glow for All Admin Modules --}}
        <div class="pointer-events-none fixed inset-0 bg-[linear-gradient(to_right,rgba(8,145,178,0.04)_1px,transparent_1px),linear-gradient(to_bottom,rgba(192,38,211,0.035)_1px,transparent_1px)] bg-[size:44px_44px] dark:bg-[linear-gradient(to_right,rgba(34,211,238,0.035)_1px,transparent_1px),linear-gradient(to_bottom,rgba(217,70,239,0.03)_1px,transparent_1px)]"></div>
        <div class="pointer-events-none fixed -top-40 right-1/4 h-[360px] w-[360px] rounded-full bg-cyan-500/8 blur-[140px] dark:bg-cyan-500/12"></div>
        <div class="pointer-events-none fixed bottom-0 left-1/3 h-[340px] w-[340px] rounded-full bg-fuchsia-500/8 blur-[140px] dark:bg-fuchsia-600/10"></div>

        <flux:sidebar sticky collapsible="mobile" class="border-e border-cyan-600/20 bg-white/95 backdrop-blur-xl dark:border-cyan-400/20 dark:bg-[#0A0D16]/95">
            <div class="h-0.5 w-full bg-gradient-to-r from-cyan-400 via-fuchsia-500 to-yellow-300"></div>
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <div class="mx-3 mb-1 flex items-center justify-between border border-cyan-600/20 bg-cyan-500/5 px-2.5 py-1.5 font-mono text-[10px] tracking-widest text-cyan-700 uppercase dark:border-cyan-400/25 dark:bg-cyan-400/5 dark:text-cyan-300">
                <span class="flex items-center gap-1.5">
                    <span class="size-1.5 animate-pulse rounded-full bg-cyan-500 dark:bg-cyan-400"></span>
                    SYS // CONSOLE
                </span>
                <button
                    type="button"
                    x-on:click="$flux.appearance = $flux.dark ? 'light' : 'dark'"
                    title="{{ __('Toggle theme') }}"
                    class="cursor-pointer text-slate-600 transition hover:text-cyan-700 dark:text-zinc-400 dark:hover:text-cyan-300"
                >
                    <span x-show="$flux.dark">☀</span>
                    <span x-show="! $flux.dark" x-cloak>☾</span>
                </button>
            </div>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')" class="grid">
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Portfolio')" class="grid">
                    <flux:sidebar.item icon="user" :href="route('admin.profile')" :current="request()->routeIs('admin.profile')" wire:navigate>
                        {{ __('Profile & Hero') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="folder-git-2" :href="route('admin.projects')" :current="request()->routeIs('admin.projects')" wire:navigate>
                        {{ __('Projects') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="book-open-text" :href="route('admin.experience-skills')" :current="request()->routeIs('admin.experience-skills')" wire:navigate>
                        {{ __('Experience & Skills') }}
                    </flux:sidebar.item>

                    @php
                        $unreadMessagesBadge = \Illuminate\Support\Facades\Schema::hasTable('messages')
                            ? \App\Models\Message::unread()->count()
                            : 0;
                    @endphp
                    <flux:sidebar.item
                        icon="inbox"
                        :href="route('admin.messages')"
                        :current="request()->routeIs('admin.messages')"
                        :badge="$unreadMessagesBadge > 0 ? $unreadMessagesBadge : null"
                        wire:navigate
                    >
                        {{ __('Messages') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            {{-- <flux:sidebar.nav>
                <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                    {{ __('Repository') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                    {{ __('Documentation') }}
                </flux:sidebar.item>
            </flux:sidebar.nav> --}}

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
