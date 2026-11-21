@php
    $profile = $this->profile;
    $bannerUrl = $profile->bannerUrl();
    $avatarUrl = $profile->avatarUrl();
    $resumeUrl = $profile->resolvedResumeUrl();
    $featuredList = $this->projects->where('is_featured', true)->take(6);
    $featuredProjects = $featuredList->isNotEmpty() ? $featuredList : $this->projects->take(6);
    $totalProjects = $this->projects->count();
    $filteredSkills = $skillCategoryFilter === 'all'
        ? $this->skills
        : $this->skills->where('category', $skillCategoryFilter);

    $skillDescriptions = [
        'backend' => [
            'code' => 'SYS.BACKEND',
            'title' => 'Server Logic & Business Systems',
            'summary' => 'Powering secure logins, payment/data workflows, and reliable behind-the-scenes application logic.',
            'border' => 'border-cyan-600/30 hover:border-cyan-600 dark:border-cyan-400/35 dark:hover:border-cyan-400',
            'accent' => 'text-cyan-700 dark:text-cyan-300',
            'bar' => 'from-cyan-500 to-sky-500 dark:from-cyan-400 dark:to-sky-500',
            'dot' => 'bg-cyan-500 dark:bg-cyan-400',
        ],
        'frontend' => [
            'code' => 'UI.FRONTEND',
            'title' => 'User Interface & Mobile Experience',
            'summary' => 'Designing clean, responsive screens that feel fast and effortless on phones, tablets, and desktops.',
            'border' => 'border-fuchsia-600/30 hover:border-fuchsia-600 dark:border-fuchsia-500/35 dark:hover:border-fuchsia-400',
            'accent' => 'text-fuchsia-700 dark:text-fuchsia-300',
            'bar' => 'from-fuchsia-500 to-pink-500',
            'dot' => 'bg-fuchsia-500 dark:bg-fuchsia-400',
        ],
        'database' => [
            'code' => 'DB.STORAGE',
            'title' => 'Data Storage & Query Speed',
            'summary' => 'Structuring, migrating, and tuning databases so business records stay accurate and load instantly.',
            'border' => 'border-amber-500/35 hover:border-amber-600 dark:border-yellow-400/35 dark:hover:border-yellow-300',
            'accent' => 'text-amber-700 dark:text-yellow-300',
            'bar' => 'from-amber-400 to-orange-500 dark:from-yellow-300 dark:to-amber-500',
            'dot' => 'bg-amber-500 dark:bg-yellow-300',
        ],
        'devops_tools' => [
            'code' => 'OPS.TOOLS',
            'title' => 'Deployment, Testing & Teamwork',
            'summary' => 'Safely shipping updates to live servers, verifying quality, and collaborating smoothly with teams.',
            'border' => 'border-emerald-600/30 hover:border-emerald-600 dark:border-emerald-400/35 dark:hover:border-emerald-400',
            'accent' => 'text-emerald-700 dark:text-emerald-300',
            'bar' => 'from-emerald-500 to-teal-500 dark:from-emerald-400 dark:to-teal-500',
            'dot' => 'bg-emerald-500 dark:bg-emerald-400',
        ],
    ];
@endphp

<div class="relative min-h-screen overflow-x-clip bg-slate-50 text-slate-900 selection:bg-cyan-400 selection:text-zinc-950 dark:bg-[#06070B] dark:text-zinc-100">
    {{-- Cyberpunk Grid, Scanlines & Ambient Neon Glows --}}
    <div class="pointer-events-none fixed inset-0 bg-[linear-gradient(to_right,rgba(8,145,178,0.06)_1px,transparent_1px),linear-gradient(to_bottom,rgba(192,38,211,0.05)_1px,transparent_1px)] bg-[size:44px_44px] dark:bg-[linear-gradient(to_right,rgba(34,211,238,0.04)_1px,transparent_1px),linear-gradient(to_bottom,rgba(217,70,239,0.04)_1px,transparent_1px)]"></div>
    <div class="pointer-events-none fixed inset-0 opacity-5 bg-[repeating-linear-gradient(to_bottom,transparent_0px,transparent_2px,rgba(0,0,0,0.35)_3px)] dark:opacity-25"></div>
    <div class="pointer-events-none fixed -top-40 left-1/4 h-[420px] w-[420px] rounded-full bg-cyan-500/10 blur-[150px] dark:bg-cyan-500/15"></div>
    <div class="pointer-events-none fixed top-1/3 -right-20 h-[420px] w-[420px] rounded-full bg-fuchsia-500/10 blur-[150px] dark:bg-fuchsia-600/15"></div>

    {{-- Cyberpunk HUD Navbar (Fixed Top + Active Section Highlight + Theme Toggle + Mobile Drawer) --}}
    <header
        x-data="{
            active: 'home',
            mobileMenuOpen: false,
            scrollProgress: 0,
            updateActive() {
                const sections = ['home', 'projects', 'experience', 'skills', 'contact'];
                const pos = window.scrollY + 140;
                for (const id of sections) {
                    const el = document.getElementById(id);
                    if (el && pos >= el.offsetTop) {
                        this.active = id;
                    }
                }
                const docHeight = document.documentElement.scrollHeight - window.innerHeight;
                this.scrollProgress = docHeight > 0
                    ? Math.min(100, Math.max(0, (window.scrollY / docHeight) * 100))
                    : 0;
            }
        }"
        x-init="updateActive()"
        x-on:scroll.window.passive="updateActive()"
        x-on:resize.window.passive="updateActive()"
        x-on:keydown.escape.window="mobileMenuOpen = false"
        class="fixed top-0 right-0 left-0 z-50 border-b border-cyan-600/20 bg-white/90 backdrop-blur-xl dark:border-cyan-400/25 dark:bg-[#06070B]/90"
    >
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-3 px-4 py-3.5 sm:px-6 lg:px-8">
            <a href="#home" x-on:click="active = 'home'; mobileMenuOpen = false" class="group flex min-w-0 items-center gap-3">
                <span
                    class="flex size-9 shrink-0 items-center justify-center bg-cyan-500 font-mono text-xs font-black text-zinc-950 shadow-[0_0_18px_rgba(34,211,238,0.4)] transition group-hover:bg-fuchsia-400 dark:bg-cyan-400"
                    style="clip-path: polygon(0 0, calc(100% - 8px) 0, 100% 8px, 100% 100%, 8px 100%, 0 calc(100% - 8px));"
                >
                    {{ \Illuminate\Support\Str::initials($profile->name ?: 'NK', true) }}
                </span>
                <div class="min-w-0">
                    <div class="truncate text-sm font-black tracking-wider text-slate-900 uppercase dark:text-white">{{ $profile->name }}</div>
                    <div class="flex items-center gap-1.5 font-mono text-[10px] tracking-widest text-cyan-700 dark:text-cyan-400">
                        <span class="size-1.5 shrink-0 animate-pulse rounded-full bg-cyan-600 dark:bg-cyan-400"></span>
                        SYS // ONLINE
                    </div>
                </div>
            </a>

            <div class="flex items-center gap-2">
                <nav class="hidden items-center gap-1 border border-cyan-600/20 bg-slate-100/90 px-2 py-1 font-mono text-xs uppercase tracking-wider md:flex dark:border-cyan-400/20 dark:bg-zinc-950/80">
                    <a
                        href="#home"
                        x-on:click="active = 'home'"
                        :class="active === 'home' ? 'bg-cyan-500 text-zinc-950 font-black shadow-[0_0_14px_rgba(34,211,238,0.35)] dark:bg-cyan-400' : 'text-slate-700 hover:bg-cyan-500/15 hover:text-cyan-700 dark:text-zinc-300 dark:hover:bg-cyan-400/15 dark:hover:text-cyan-300'"
                        class="px-3 py-1.5 transition"
                    >{{ __('Home') }}</a>
                    <a
                        href="#projects"
                        x-on:click="active = 'projects'"
                        :class="active === 'projects' ? 'bg-cyan-500 text-zinc-950 font-black shadow-[0_0_14px_rgba(34,211,238,0.35)] dark:bg-cyan-400' : 'text-slate-700 hover:bg-cyan-500/15 hover:text-cyan-700 dark:text-zinc-300 dark:hover:bg-cyan-400/15 dark:hover:text-cyan-300'"
                        class="px-3 py-1.5 transition"
                    >{{ __('Projects') }}</a>
                    <a
                        href="#experience"
                        x-on:click="active = 'experience'"
                        :class="active === 'experience' ? 'bg-cyan-500 text-zinc-950 font-black shadow-[0_0_14px_rgba(34,211,238,0.35)] dark:bg-cyan-400' : 'text-slate-700 hover:bg-fuchsia-500/15 hover:text-fuchsia-700 dark:text-zinc-300 dark:hover:bg-fuchsia-500/15 dark:hover:text-fuchsia-300'"
                        class="px-3 py-1.5 transition"
                    >{{ __('Experience') }}</a>
                    <a
                        href="#skills"
                        x-on:click="active = 'skills'"
                        :class="active === 'skills' ? 'bg-cyan-500 text-zinc-950 font-black shadow-[0_0_14px_rgba(34,211,238,0.35)] dark:bg-cyan-400' : 'text-slate-700 hover:bg-amber-400/20 hover:text-amber-700 dark:text-zinc-300 dark:hover:bg-yellow-300/15 dark:hover:text-yellow-300'"
                        class="px-3 py-1.5 transition"
                    >{{ __('Skills') }}</a>
                    <a
                        href="#contact"
                        x-on:click="active = 'contact'"
                        :class="active === 'contact' ? 'bg-cyan-500 text-zinc-950 font-black shadow-[0_0_14px_rgba(34,211,238,0.35)] dark:bg-cyan-400' : 'text-slate-700 hover:bg-cyan-500/15 hover:text-cyan-700 dark:text-zinc-300 dark:hover:bg-cyan-400/15 dark:hover:text-cyan-300'"
                        class="px-3 py-1.5 transition"
                    >{{ __('Contact') }}</a>
                </nav>

                {{-- Light / Dark Mode Toggle --}}
                <button
                    type="button"
                    x-on:click="$flux.appearance = $flux.dark ? 'light' : 'dark'"
                    :aria-label="$flux.dark ? '{{ __('Switch to light mode') }}' : '{{ __('Switch to dark mode') }}'"
                    title="{{ __('Toggle theme') }}"
                    class="inline-flex size-10 shrink-0 cursor-pointer items-center justify-center border border-cyan-600/30 bg-cyan-500/10 text-cyan-700 transition hover:bg-cyan-500 hover:text-zinc-950 dark:border-cyan-400/35 dark:bg-cyan-400/10 dark:text-cyan-300 dark:hover:bg-cyan-400 dark:hover:text-zinc-950"
                >
                    <svg x-show="$flux.dark" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="4" />
                        <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41" />
                    </svg>
                    <svg x-show="! $flux.dark" x-cloak class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
                    </svg>
                </button>

                {{-- Mobile Menu Toggle --}}
                <button
                    type="button"
                    x-on:click="mobileMenuOpen = ! mobileMenuOpen"
                    :aria-expanded="mobileMenuOpen"
                    aria-label="{{ __('Toggle navigation menu') }}"
                    class="inline-flex size-10 shrink-0 cursor-pointer items-center justify-center border border-cyan-600/35 bg-cyan-500/10 font-mono text-cyan-700 transition hover:bg-cyan-500 hover:text-zinc-950 md:hidden dark:border-cyan-400/40 dark:bg-cyan-400/10 dark:text-cyan-300 dark:hover:bg-cyan-400 dark:hover:text-zinc-950"
                >
                    <svg x-show="! mobileMenuOpen" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="square" d="M4 7h16M4 12h16M4 17h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="square" d="M6 6l12 12M18 6L6 18" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Navigation Drawer --}}
        <nav
            x-show="mobileMenuOpen"
            x-transition.opacity.duration.150ms
            x-cloak
            class="border-t border-cyan-600/20 bg-white/95 px-4 py-3 font-mono text-xs uppercase tracking-wider backdrop-blur-xl md:hidden dark:border-cyan-400/20 dark:bg-[#06070B]/95"
        >
            <div class="grid grid-cols-2 gap-2">
                <a
                    href="#home"
                    x-on:click="active = 'home'; mobileMenuOpen = false"
                    :class="active === 'home' ? 'border-cyan-500 bg-cyan-500 text-zinc-950 font-black dark:border-cyan-400 dark:bg-cyan-400' : 'border-slate-200 bg-slate-100 text-slate-700 hover:border-cyan-500/50 dark:border-white/15 dark:bg-white/5 dark:text-zinc-200 dark:hover:border-cyan-400/50'"
                    class="border px-3 py-2.5 text-center transition"
                >{{ __('Home') }}</a>
                <a
                    href="#projects"
                    x-on:click="active = 'projects'; mobileMenuOpen = false"
                    :class="active === 'projects' ? 'border-cyan-500 bg-cyan-500 text-zinc-950 font-black dark:border-cyan-400 dark:bg-cyan-400' : 'border-slate-200 bg-slate-100 text-slate-700 hover:border-cyan-500/50 dark:border-white/15 dark:bg-white/5 dark:text-zinc-200 dark:hover:border-cyan-400/50'"
                    class="border px-3 py-2.5 text-center transition"
                >{{ __('Projects') }}</a>
                <a
                    href="#experience"
                    x-on:click="active = 'experience'; mobileMenuOpen = false"
                    :class="active === 'experience' ? 'border-cyan-500 bg-cyan-500 text-zinc-950 font-black dark:border-cyan-400 dark:bg-cyan-400' : 'border-slate-200 bg-slate-100 text-slate-700 hover:border-fuchsia-500/50 dark:border-white/15 dark:bg-white/5 dark:text-zinc-200 dark:hover:border-fuchsia-400/50'"
                    class="border px-3 py-2.5 text-center transition"
                >{{ __('Experience') }}</a>
                <a
                    href="#skills"
                    x-on:click="active = 'skills'; mobileMenuOpen = false"
                    :class="active === 'skills' ? 'border-cyan-500 bg-cyan-500 text-zinc-950 font-black dark:border-cyan-400 dark:bg-cyan-400' : 'border-slate-200 bg-slate-100 text-slate-700 hover:border-amber-500/50 dark:border-white/15 dark:bg-white/5 dark:text-zinc-200 dark:hover:border-yellow-300/50'"
                    class="border px-3 py-2.5 text-center transition"
                >{{ __('Skills') }}</a>
                <a
                    href="#contact"
                    x-on:click="active = 'contact'; mobileMenuOpen = false"
                    :class="active === 'contact' ? 'border-cyan-500 bg-cyan-500 text-zinc-950 font-black dark:border-cyan-400 dark:bg-cyan-400' : 'border-cyan-600/40 bg-cyan-500/10 text-cyan-700 dark:border-cyan-400/40 dark:bg-cyan-400/10 dark:text-cyan-300'"
                    class="col-span-2 border px-3 py-2.5 text-center transition"
                >{{ __('Contact') }}</a>
            </div>
        </nav>

        {{-- Scroll Progress Line --}}
        <div class="pointer-events-none h-[2px] w-full bg-slate-200/70 dark:bg-white/5">
            <div
                class="h-full bg-gradient-to-r from-cyan-500 via-fuchsia-500 to-amber-400 shadow-[0_0_10px_rgba(34,211,238,0.8)] transition-[width] duration-75 ease-out dark:from-cyan-400 dark:via-fuchsia-500 dark:to-yellow-300"
                :style="`width: ${scrollProgress}%`"
            ></div>
        </div>
    </header>

    {{-- Hero Section --}}
    <main id="main">
    <section id="home" class="relative border-b border-cyan-600/20 pt-16 dark:border-cyan-400/20">
        @if ($bannerUrl)
            <div
                class="absolute inset-0 bg-cover bg-center opacity-15 dark:opacity-25"
                style="background-image: url('{{ $bannerUrl }}');"
            ></div>
            <div class="absolute inset-0 bg-gradient-to-b from-slate-50/85 via-slate-50/90 to-slate-50 dark:from-[#06070B]/80 dark:via-[#06070B]/85 dark:to-[#06070B]"></div>
        @endif

        <div class="relative mx-auto max-w-6xl px-4 py-12 sm:px-6 sm:py-20 lg:px-8 lg:py-24">
            <div class="grid items-center gap-10 lg:grid-cols-12 lg:gap-14">
                <div class="space-y-6 lg:col-span-7">
                    <div class="flex flex-wrap items-center gap-2.5 font-mono text-xs uppercase tracking-wider sm:gap-3">
                        <span class="inline-flex items-center gap-2 border border-cyan-600/40 bg-cyan-500/10 px-3 py-1.5 text-cyan-800 shadow-[0_0_15px_rgba(34,211,238,0.12)] sm:px-3.5 dark:border-cyan-400/50 dark:bg-cyan-400/10 dark:text-cyan-300 dark:shadow-[0_0_15px_rgba(34,211,238,0.2)]">
                            @if ($profile->is_available)
                                <span class="relative flex size-2">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-cyan-500 opacity-75 dark:bg-cyan-400"></span>
                                    <span class="relative inline-flex size-2 rounded-full bg-cyan-600 dark:bg-cyan-300"></span>
                                </span>
                                {{ __('Available for Work') }}
                            @else
                                <span class="size-2 rounded-full bg-slate-400 dark:bg-zinc-500"></span>
                                {{ __('Currently Unavailable') }}
                            @endif
                        </span>

                        <span class="border border-fuchsia-600/35 bg-fuchsia-500/10 px-3 py-1.5 text-fuchsia-800 sm:px-3.5 dark:border-fuchsia-500/40 dark:text-fuchsia-300">
                            NODE // {{ $profile->location }}
                        </span>
                    </div>

                    <h1 class="text-3xl font-black tracking-tight sm:text-5xl lg:text-6xl lg:leading-[1.06]">
                        <span class="bg-gradient-to-r from-slate-900 via-cyan-700 to-fuchsia-700 bg-clip-text text-transparent dark:from-white dark:via-cyan-200 dark:to-fuchsia-300">
                            {{ $profile->headline }}
                        </span>
                    </h1>

                    <div class="border-l-2 border-cyan-600 pl-4 dark:border-cyan-400">
                        <p class="font-mono text-base font-semibold text-cyan-700 sm:text-lg dark:text-cyan-300">
                            &gt;_ {{ $profile->tagline }}
                        </p>
                    </div>

                    @if ($profile->bio)
                        <p class="max-w-2xl text-base leading-relaxed text-slate-600 dark:text-zinc-300">
                            {{ $profile->bio }}
                        </p>
                    @endif

                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <a
                            href="#contact"
                            class="inline-flex items-center justify-center bg-cyan-500 px-6 py-3 font-mono text-xs font-black uppercase tracking-wider text-zinc-950 shadow-[0_0_24px_rgba(34,211,238,0.35)] transition hover:bg-yellow-300 dark:bg-cyan-400"
                            style="clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 12px, 100% 100%, 12px 100%, 0 calc(100% - 12px));"
                        >
                            {{ __('Contact Me') }}
                        </a>

                        <a
                            href="#projects"
                            class="inline-flex items-center justify-center border border-fuchsia-600/60 bg-fuchsia-500/10 px-6 py-3 font-mono text-xs font-bold uppercase tracking-wider text-fuchsia-700 transition hover:bg-fuchsia-500/20 dark:border-fuchsia-500/70 dark:text-fuchsia-300 dark:hover:bg-fuchsia-500/25"
                        >
                            {{ __('View Projects') }}
                        </a>

                        @if ($resumeUrl)
                            <a
                                href="{{ $resumeUrl }}"
                                target="_blank"
                                class="inline-flex items-center justify-center border border-slate-300 bg-white px-4 py-3 font-mono text-xs font-semibold uppercase tracking-wider text-slate-700 transition hover:border-cyan-600 hover:text-cyan-700 dark:border-white/15 dark:bg-white/5 dark:text-zinc-200 dark:hover:border-cyan-400/60 dark:hover:text-cyan-300"
                            >
                                {{ __('Resume / CV') }} ↗
                            </a>
                        @endif

                        @if ($profile->github_url)
                            <a
                                href="{{ $profile->github_url }}"
                                target="_blank"
                                class="inline-flex items-center justify-center border border-slate-300 bg-white px-4 py-3 font-mono text-xs font-semibold uppercase tracking-wider text-slate-700 transition hover:border-cyan-600 hover:text-cyan-700 dark:border-white/15 dark:bg-white/5 dark:text-zinc-200 dark:hover:border-cyan-400/60 dark:hover:text-cyan-300"
                            >
                                {{ __('GitHub') }} ↗
                            </a>
                        @endif

                        @if ($profile->linkedin_url)
                            <a
                                href="{{ $profile->linkedin_url }}"
                                target="_blank"
                                class="inline-flex items-center justify-center border border-slate-300 bg-white px-4 py-3 font-mono text-xs font-semibold uppercase tracking-wider text-slate-700 transition hover:border-cyan-600 hover:text-cyan-700 dark:border-white/15 dark:bg-white/5 dark:text-zinc-200 dark:hover:border-cyan-400/60 dark:hover:text-cyan-300"
                            >
                                {{ __('LinkedIn') }} ↗
                            </a>
                        @endif
                    </div>
                </div>

                {{-- 3D Layered Cyberpunk Identity Rig --}}
                <div class="lg:col-span-5">
                    <div class="relative">
                        {{-- Offset Neon Wireframe Shadow Layer --}}
                        <div class="pointer-events-none absolute inset-0 translate-x-2 translate-y-2 border border-fuchsia-500/40 bg-fuchsia-500/5 sm:translate-x-3 sm:translate-y-3"></div>

                        <div
                            class="relative border border-cyan-600/40 bg-white p-4 shadow-xl sm:p-6 dark:border-cyan-400/50 dark:bg-[#0A0D16] dark:shadow-[0_0_45px_rgba(34,211,238,0.16)]"
                            style="clip-path: polygon(0 0, calc(100% - 22px) 0, 100% 22px, 100% 100%, 22px 100%, 0 calc(100% - 22px));"
                        >
                            <div class="mb-5 flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 pb-3 font-mono text-[11px] uppercase tracking-widest dark:border-white/10">
                                <span class="text-cyan-700 dark:text-cyan-400">NETRUNNER_ID // 001</span>
                                <span class="bg-yellow-300 px-2 py-0.5 text-[10px] font-black text-zinc-950">VERIFIED</span>
                            </div>

                            <div class="flex flex-col items-center gap-4 text-center sm:flex-row sm:gap-5 sm:text-left">
                                @if ($avatarUrl)
                                    <div class="relative shrink-0">
                                        <div class="absolute -inset-1.5 bg-gradient-to-tr from-cyan-400 via-fuchsia-500 to-yellow-300 opacity-60 blur-sm"></div>
                                        <img
                                            src="{{ $avatarUrl }}"
                                            alt="{{ $profile->name }}"
                                            fetchpriority="high"
                                            class="relative size-24 border-2 border-cyan-500 object-cover sm:size-32 dark:border-cyan-400"
                                        />
                                    </div>
                                @else
                                    <div class="flex size-24 shrink-0 items-center justify-center border-2 border-cyan-500 bg-gradient-to-br from-cyan-500/15 to-fuchsia-500/15 font-mono text-2xl font-black text-cyan-700 sm:size-32 sm:text-3xl dark:border-cyan-400 dark:from-cyan-500/20 dark:to-fuchsia-500/20 dark:text-cyan-300">
                                        {{ \Illuminate\Support\Str::initials($profile->name ?: 'NK', true) }}
                                    </div>
                                @endif

                                <div class="min-w-0 space-y-1.5">
                                    <div class="font-mono text-[11px] font-bold uppercase tracking-widest text-fuchsia-700 dark:text-fuchsia-400">
                                        // {{ __('DEVELOPER_PROFILE') }}
                                    </div>
                                    <h2 class="text-xl font-black tracking-tight text-slate-900 sm:text-2xl dark:text-white">{{ $profile->name }}</h2>
                                </div>
                            </div>

                            <div class="mt-6 grid grid-cols-3 gap-2 sm:gap-2.5">
                                <div class="border border-cyan-600/25 bg-cyan-500/5 p-2.5 text-center sm:p-3 dark:border-cyan-400/25 dark:bg-cyan-400/5">
                                    <div class="font-mono text-xl font-black text-cyan-700 sm:text-2xl dark:text-cyan-300">{{ $totalProjects }}</div>
                                    <div class="mt-0.5 font-mono text-[10px] uppercase tracking-wider text-slate-600 dark:text-zinc-400">{{ __('Projects') }}</div>
                                </div>
                                <div class="border border-fuchsia-600/25 bg-fuchsia-500/5 p-2.5 text-center sm:p-3 dark:border-fuchsia-500/25 dark:bg-fuchsia-500/5">
                                    <div class="font-mono text-xl font-black text-fuchsia-700 sm:text-2xl dark:text-fuchsia-300">{{ $this->experiences->count() }}</div>
                                    <div class="mt-0.5 font-mono text-[10px] uppercase tracking-wider text-slate-600 dark:text-zinc-400">{{ __('Roles') }}</div>
                                </div>
                                <div class="border border-amber-500/30 bg-amber-500/5 p-2.5 text-center sm:p-3 dark:border-yellow-400/25 dark:bg-yellow-400/5">
                                    <div class="font-mono text-xl font-black text-amber-700 sm:text-2xl dark:text-yellow-300">{{ $this->skills->count() }}</div>
                                    <div class="mt-0.5 font-mono text-[10px] uppercase tracking-wider text-slate-600 dark:text-zinc-400">{{ __('Skills') }}</div>
                                </div>
                            </div>

                            <div class="mt-4 flex flex-wrap items-center justify-between gap-2 border-t border-slate-200 pt-3 font-mono text-[10px] uppercase tracking-wider text-slate-500 dark:border-white/10 dark:text-zinc-500">
                                <span>LATENCY: 12MS</span>
                                <span class="text-cyan-700 dark:text-cyan-400">READY_TO_DEPLOY</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Projects Section: Spotlight + Clean Image Cards --}}
    <section id="projects" class="relative mx-auto max-w-6xl scroll-mt-20 px-4 py-16 sm:px-6 lg:px-8">
        <div class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="font-mono text-xs font-bold uppercase tracking-widest text-cyan-700 dark:text-cyan-400">
                    01 // {{ __('DEPLOYED_SYSTEMS') }}
                </p>
                <h2 class="mt-2 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl dark:text-white">
                    {{ __('Projects') }}
                </h2>
                <p class="mt-2 max-w-xl text-sm text-slate-600 dark:text-zinc-400">
                    {{ __('Selected builds, systems, and web applications.') }}
                </p>
            </div>

            @if ($totalProjects > 0)
                <button
                    type="button"
                    wire:click="$set('showAllProjectsModal', true)"
                    class="inline-flex w-fit cursor-pointer items-center gap-2 border border-cyan-600 bg-cyan-500/10 px-5 py-2.5 font-mono text-xs font-bold uppercase tracking-wider text-cyan-800 shadow-[0_0_20px_rgba(34,211,238,0.14)] transition hover:bg-cyan-500 hover:text-zinc-950 dark:border-cyan-400 dark:bg-cyan-400/10 dark:text-cyan-300 dark:shadow-[0_0_20px_rgba(34,211,238,0.18)] dark:hover:bg-cyan-400"
                    style="clip-path: polygon(0 0, calc(100% - 10px) 0, 100% 10px, 100% 100%, 10px 100%, 0 calc(100% - 10px));"
                >
                    {{ __('All Projects') }} ({{ $totalProjects }}) →
                </button>
            @endif
        </div>

        @if ($featuredProjects->isEmpty())
            <div class="border border-dashed border-cyan-600/35 p-12 text-center font-mono text-sm text-slate-500 dark:border-cyan-400/30 dark:text-zinc-400">
                {{ __('Projects will be published soon.') }}
            </div>
        @else
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredProjects as $project)
                    <article
                        wire:key="public-project-{{ $project->id }}"
                        class="group flex flex-col border border-slate-200 bg-white shadow-sm transition hover:border-cyan-500/70 hover:shadow-[0_0_28px_rgba(34,211,238,0.14)] dark:border-white/15 dark:bg-[#0A0D16] dark:shadow-none dark:hover:border-cyan-400/70"
                    >
                        <div class="relative h-48 w-full overflow-hidden border-b border-slate-200 bg-slate-100 dark:border-white/15 dark:bg-zinc-950">
                            <span class="pointer-events-none absolute top-2 left-2 z-10 size-2.5 border-t-2 border-l-2 border-cyan-500/80 dark:border-cyan-400/80"></span>
                            <span class="pointer-events-none absolute right-2 bottom-2 z-10 size-2.5 border-r-2 border-b-2 border-fuchsia-500/80 dark:border-fuchsia-400/80"></span>

                            @if ($project->is_featured)
                                <span class="absolute top-2.5 right-2.5 z-10 bg-yellow-300 px-2 py-0.5 font-mono text-[10px] font-black uppercase text-zinc-950 shadow">
                                    {{ __('Featured') }}
                                </span>
                            @endif

                            @if ($project->thumbnailUrl())
                                <img
                                    src="{{ $project->thumbnailUrl() }}"
                                    alt="{{ $project->title }}"
                                    loading="lazy"
                                    class="h-full w-full object-cover object-top transition duration-500 group-hover:scale-[1.03]"
                                />
                            @else
                                <div class="flex h-full flex-col items-center justify-center bg-[radial-gradient(circle_at_center,rgba(34,211,238,0.10),transparent_70%)] p-6 text-center">
                                    <div class="border border-cyan-600/30 bg-cyan-500/10 px-3 py-1 font-mono text-[11px] font-bold tracking-widest text-cyan-800 uppercase dark:border-cyan-400/30 dark:bg-cyan-400/5 dark:text-cyan-300">
                                        SYS_BUILD // 0{{ $loop->iteration }}
                                    </div>
                                    <div class="mt-3 flex items-center gap-2 font-mono text-[10px] text-slate-500 dark:text-zinc-500">
                                        <span class="h-px w-8 bg-cyan-500/40 dark:bg-cyan-400/30"></span>
                                        <span>{{ __('NO PREVIEW IMAGE') }}</span>
                                        <span class="h-px w-8 bg-fuchsia-500/40 dark:bg-fuchsia-400/30"></span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-1 flex-col justify-between p-5 text-center">
                            <div>
                                <h3 class="text-lg font-black uppercase tracking-wide text-slate-900 transition group-hover:text-cyan-700 dark:text-white dark:group-hover:text-cyan-300">
                                    {{ $project->title }}
                                </h3>

                                <div class="mt-3 flex flex-wrap justify-center gap-1.5">
                                    @foreach (array_slice($project->tech_stack, 0, 4) as $tech)
                                        <span class="border border-cyan-600/25 bg-cyan-50 px-2.5 py-0.5 font-mono text-[11px] font-semibold text-cyan-800 dark:border-cyan-400/25 dark:bg-cyan-400/5 dark:text-cyan-200">
                                            {{ $tech }}
                                        </span>
                                    @endforeach
                                    @if (count($project->tech_stack) > 4)
                                        <span class="border border-fuchsia-600/30 bg-fuchsia-50 px-2 py-0.5 font-mono text-[11px] font-bold text-fuchsia-700 dark:border-fuchsia-500/30 dark:bg-fuchsia-500/10 dark:text-fuchsia-300">
                                            +{{ count($project->tech_stack) - 4 }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-5 space-y-2 border-t border-slate-200 pt-4 dark:border-white/10">
                                @if ($project->demo_url || $project->repo_url)
                                    <div class="flex gap-2 font-mono text-xs font-bold uppercase">
                                        @if ($project->demo_url)
                                            <a
                                                href="{{ $project->demo_url }}"
                                                target="_blank"
                                                class="flex-1 bg-cyan-500 px-3 py-2 text-center text-zinc-950 transition hover:bg-yellow-300 dark:bg-cyan-400"
                                            >
                                                {{ __('Demo →') }}
                                            </a>
                                        @endif
                                        @if ($project->repo_url)
                                            <a
                                                href="{{ $project->repo_url }}"
                                                target="_blank"
                                                class="flex-1 border border-fuchsia-600/50 bg-fuchsia-500/10 px-3 py-2 text-center text-fuchsia-700 transition hover:bg-fuchsia-500/20 dark:border-fuchsia-500/60 dark:text-fuchsia-300"
                                            >
                                                {{ __('Repository →') }}
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                <button
                                    type="button"
                                    wire:click="viewProjectDetails({{ $project->id }})"
                                    class="flex w-full cursor-pointer items-center justify-center gap-2 border border-cyan-600/40 bg-cyan-50 px-3 py-2 font-mono text-xs font-bold uppercase tracking-wider text-cyan-800 transition hover:border-cyan-600 hover:bg-cyan-500 hover:text-zinc-950 dark:border-cyan-400/50 dark:bg-cyan-400/5 dark:text-cyan-300 dark:hover:border-cyan-400 dark:hover:bg-cyan-400"
                                >
                                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                        <path d="M4 8V4h4M20 8V4h-4M4 16v4h4M20 16v4h-4" />
                                    </svg>
                                    <span>{{ __('Show Details') }}</span>
                                </button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Experience Section: Minimalist Cyber Infographic --}}
    <section id="experience" class="relative scroll-mt-20 border-y border-fuchsia-500/20 bg-slate-100/80 py-16 dark:bg-zinc-950/75">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="font-mono text-xs font-bold uppercase tracking-widest text-fuchsia-700 dark:text-fuchsia-400">
                        02 // {{ __('CAREER_TIMELINE') }}
                    </p>
                    <h2 class="mt-2 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl dark:text-white">
                        {{ __('Experience') }}
                    </h2>
                </div>
                <p class="max-w-md text-sm text-slate-600 dark:text-zinc-400">
                    {{ __('Concise career timeline from past roles to the present.') }}
                </p>
            </div>

            <div class="relative space-y-5 before:absolute before:top-6 before:bottom-6 before:left-5 before:hidden before:w-px before:bg-gradient-to-b before:from-cyan-500 before:via-fuchsia-500 before:to-amber-400 md:before:block dark:before:from-cyan-400 dark:before:to-yellow-300">
                @foreach ($this->experiences as $experience)
                    <div
                        wire:key="public-experience-{{ $experience->id }}"
                        class="relative flex flex-col gap-4 md:flex-row md:items-start md:gap-6"
                    >
                        {{-- Timeline Node + Period --}}
                        <div class="flex items-center gap-3 md:w-60 md:shrink-0 md:pt-4">
                            <div class="relative z-10 flex size-10 shrink-0 items-center justify-center border border-cyan-600 bg-white font-mono text-xs font-black text-cyan-700 shadow-[0_0_15px_rgba(34,211,238,0.18)] dark:border-cyan-400 dark:bg-[#06070B] dark:text-cyan-300 dark:shadow-[0_0_15px_rgba(34,211,238,0.25)]">
                                0{{ $loop->iteration }}
                            </div>
                            <div class="min-w-0">
                                <div class="inline-block border border-fuchsia-600/35 bg-fuchsia-500/10 px-2.5 py-0.5 font-mono text-[11px] font-semibold text-fuchsia-800 dark:border-fuchsia-500/40 dark:text-fuchsia-300">
                                    {{ $experience->period }}
                                </div>
                                @if ($experience->location)
                                    <div class="mt-1 font-mono text-[11px] text-slate-600 dark:text-zinc-400">
                                        {{ $experience->location }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Minimalist Experience Card with Expandable Details --}}
                        <article
                            x-data="{ open: false }"
                            class="flex-1 border border-slate-200 bg-white p-4 shadow-sm transition hover:border-cyan-500/60 sm:p-5 dark:border-white/15 dark:bg-[#0A0D16] dark:shadow-none dark:hover:border-cyan-400/60"
                        >
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="flex min-w-0 flex-1 items-center gap-3">
                                    <div class="flex size-9 shrink-0 items-center justify-center border border-cyan-600/35 bg-cyan-500/10 font-mono text-xs font-black text-cyan-700 dark:border-cyan-400/40 dark:bg-cyan-400/10 dark:text-cyan-300">
                                        {{ $experience->companyInitial() }}
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="text-base font-bold text-slate-900 sm:text-lg dark:text-white">{{ $experience->role }}</h3>
                                        <div class="text-sm font-medium text-cyan-700 dark:text-cyan-200">{{ $experience->company }}</div>
                                    </div>
                                </div>

                                @if ($experience->description)
                                    <button
                                        type="button"
                                        x-on:click="open = ! open"
                                        class="cursor-pointer border border-cyan-600/35 bg-cyan-50 px-3 py-1 font-mono text-[11px] font-bold uppercase tracking-wider text-cyan-800 transition hover:border-cyan-600 hover:bg-cyan-500 hover:text-zinc-950 dark:border-cyan-400/35 dark:bg-cyan-400/5 dark:text-cyan-300 dark:hover:border-cyan-400 dark:hover:bg-cyan-400"
                                    >
                                        <span x-show="! open">{{ __('Details +') }}</span>
                                        <span x-show="open" x-cloak>{{ __('Hide −') }}</span>
                                    </button>
                                @endif
                            </div>

                            @if ($experience->description)
                                <div x-show="open" x-transition.opacity.duration.200ms x-cloak class="mt-3 border-l-2 border-fuchsia-500/60 pl-3.5">
                                    <p class="text-sm leading-relaxed text-slate-600 dark:text-zinc-300">
                                        {{ $experience->description }}
                                    </p>
                                </div>
                            @endif

                            @if (! empty($experience->tech_stack))
                                <div class="mt-4 flex flex-wrap gap-1.5 border-t border-slate-200 pt-3 dark:border-white/10">
                                    @foreach ($experience->tech_stack as $tech)
                                        <span class="border border-slate-200 bg-slate-100 px-2.5 py-0.5 font-mono text-[11px] text-slate-700 dark:border-white/10 dark:bg-white/5 dark:text-cyan-200">
                                            {{ $tech }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Skills Section: Clear for Technical & Non-Technical Visitors --}}
    <section id="skills" class="relative mx-auto max-w-6xl scroll-mt-20 px-4 py-20 sm:px-6 lg:px-8">
        <div class="mb-10 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="font-mono text-xs font-bold uppercase tracking-widest text-amber-700 dark:text-yellow-300">
                    03 // {{ __('CAPABILITY_MATRIX') }}
                </p>
                <h2 class="mt-2 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl dark:text-white">
                    {{ __('Skills') }}
                </h2>
                <p class="mt-2 max-w-xl text-sm text-slate-600 dark:text-zinc-400">
                    {{ __('Plain-English business value paired with the underlying tech stack.') }}
                </p>
            </div>

            <div class="flex flex-wrap gap-2 font-mono">
                <button
                    type="button"
                    wire:click="$set('skillCategoryFilter', 'all')"
                    @class([
                        'cursor-pointer px-3.5 py-2 text-xs font-bold uppercase transition',
                        'border border-cyan-500 bg-cyan-500 text-zinc-950 dark:border-cyan-400 dark:bg-cyan-400' => $skillCategoryFilter === 'all',
                        'border border-slate-300 bg-white text-slate-700 hover:border-cyan-500/60 dark:border-white/15 dark:bg-white/5 dark:text-zinc-300 dark:hover:border-cyan-400/50' => $skillCategoryFilter !== 'all',
                    ])
                >
                    {{ __('All') }} ({{ $this->skills->count() }})
                </button>

                @foreach (\App\Models\Skill::CATEGORIES as $categoryKey => $categoryLabel)
                    <button
                        type="button"
                        wire:click="$set('skillCategoryFilter', '{{ $categoryKey }}')"
                        @class([
                            'cursor-pointer px-3.5 py-2 text-xs font-bold uppercase transition',
                            'border border-cyan-500 bg-cyan-500 text-zinc-950 dark:border-cyan-400 dark:bg-cyan-400' => $skillCategoryFilter === $categoryKey,
                            'border border-slate-300 bg-white text-slate-700 hover:border-cyan-500/60 dark:border-white/15 dark:bg-white/5 dark:text-zinc-300 dark:hover:border-cyan-400/50' => $skillCategoryFilter !== $categoryKey,
                        ])
                    >
                        {{ __($categoryLabel) }}
                    </button>
                @endforeach
            </div>
        </div>

        @if ($skillCategoryFilter === 'all')
            <div class="grid gap-6 md:grid-cols-2">
                @foreach (\App\Models\Skill::CATEGORIES as $categoryKey => $categoryLabel)
                    @php
                        $categorySkills = $this->skills->where('category', $categoryKey);
                        $meta = $skillDescriptions[$categoryKey] ?? [
                            'code' => 'SYS.CORE',
                            'title' => $categoryLabel,
                            'summary' => '',
                            'border' => 'border-slate-200 dark:border-white/15',
                            'accent' => 'text-cyan-700 dark:text-cyan-300',
                            'bar' => 'from-cyan-400 to-fuchsia-400',
                            'dot' => 'bg-cyan-500 dark:bg-cyan-400',
                        ];
                    @endphp
                    @if ($categorySkills->isNotEmpty())
                        <div class="relative overflow-hidden border bg-white p-4 shadow-sm transition hover:-translate-y-0.5 sm:p-6 dark:bg-[#0A0D16] dark:shadow-none {{ $meta['border'] }}">
                            <div class="pointer-events-none absolute top-0 right-0 left-0 h-1 bg-gradient-to-r {{ $meta['bar'] }}"></div>

                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 font-mono text-[11px] font-bold uppercase tracking-widest {{ $meta['accent'] }}">
                                        <span class="size-2 shrink-0 {{ $meta['dot'] }}"></span>
                                        {{ $meta['code'] }} // {{ __($categoryLabel) }}
                                    </div>
                                    <h3 class="mt-2 text-lg font-bold text-slate-900 dark:text-white">
                                        {{ __($meta['title']) }}
                                    </h3>
                                </div>

                                <span class="shrink-0 border border-slate-200 bg-slate-100 px-2.5 py-1 font-mono text-xs font-black text-slate-900 dark:border-white/15 dark:bg-white/5 dark:text-white">
                                    {{ $categorySkills->count() }}
                                </span>
                            </div>

                            @if ($meta['summary'] !== '')
                                <p class="mt-2.5 text-sm leading-relaxed text-slate-600 dark:text-zinc-400">
                                    {{ __($meta['summary']) }}
                                </p>
                            @endif

                            <div class="mt-5 flex flex-wrap gap-2">
                                @foreach ($categorySkills as $skill)
                                    <span
                                        wire:key="public-skill-{{ $skill->id }}"
                                        class="inline-flex items-center gap-2 border border-slate-200 bg-slate-50 px-3 py-1.5 font-mono text-xs font-semibold text-slate-800 transition hover:-translate-y-0.5 hover:border-cyan-500 hover:text-cyan-700 hover:shadow-[0_0_14px_rgba(34,211,238,0.2)] dark:border-white/15 dark:bg-[#06070B] dark:text-zinc-100 dark:hover:border-cyan-400 dark:hover:text-cyan-300 dark:hover:shadow-[0_0_14px_rgba(34,211,238,0.25)]"
                                    >
                                        <span class="size-1.5 rounded-full {{ $meta['dot'] }}"></span>
                                        {{ $skill->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            @php
                $activeMeta = $skillDescriptions[$skillCategoryFilter] ?? null;
            @endphp
            <div class="border border-cyan-600/30 bg-white p-6 shadow-sm sm:p-8 dark:border-cyan-400/35 dark:bg-[#0A0D16] dark:shadow-none">
                @if ($activeMeta)
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ __($activeMeta['title']) }}</h3>
                        <p class="mt-1 text-sm text-slate-600 dark:text-zinc-400">{{ __($activeMeta['summary']) }}</p>
                    </div>
                @endif

                <div class="flex flex-wrap gap-3">
                    @foreach ($filteredSkills as $skill)
                        <span
                            wire:key="public-skill-filtered-{{ $skill->id }}"
                            class="inline-flex items-center gap-2 border border-cyan-600/30 bg-cyan-50 px-4 py-2 font-mono text-xs font-bold text-cyan-800 dark:border-cyan-400/35 dark:bg-zinc-900 dark:text-cyan-200"
                        >
                            <span class="size-1.5 bg-cyan-500 dark:bg-cyan-400"></span>
                            {{ $skill->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif
    </section>

    {{-- Contact Section: Cyberpunk Comms Deck --}}
    <section id="contact" class="relative scroll-mt-20 border-t border-cyan-600/20 bg-slate-100/80 py-20 dark:border-cyan-400/20 dark:bg-zinc-950/80">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden border border-cyan-600/35 bg-white shadow-xl dark:border-cyan-400/40 dark:bg-[#0A0D16] dark:shadow-[0_0_50px_rgba(34,211,238,0.12)]">
                <div class="h-1 w-full bg-gradient-to-r from-cyan-500 via-fuchsia-500 to-amber-400 dark:from-cyan-400 dark:to-yellow-300"></div>

                <div class="grid lg:grid-cols-12">
                    <div class="flex flex-col justify-between gap-8 border-b border-slate-200 bg-gradient-to-br from-white to-fuchsia-50/70 p-5 sm:p-8 lg:col-span-5 lg:border-r lg:border-b-0 lg:p-10 dark:border-white/10 dark:from-[#0A0D16] dark:to-fuchsia-950/20">
                        <div class="space-y-4">
                            <p class="font-mono text-xs font-bold uppercase tracking-widest text-fuchsia-700 dark:text-fuchsia-400">
                                04 // {{ __('COMMS_CHANNEL') }}
                            </p>
                            <h2 class="text-3xl font-black tracking-tight text-slate-900 sm:text-4xl dark:text-white">
                                {{ __('Get In Touch') }}
                            </h2>
                            <p class="text-sm leading-relaxed text-slate-600 dark:text-zinc-300">
                                {{ __('Ready to build a new web platform, modernize a legacy system, or collaborate on a web application? Open a direct transmission.') }}
                            </p>
                        </div>

                        <div class="space-y-3 border border-cyan-600/25 bg-cyan-500/5 p-4 text-sm sm:p-5 dark:border-cyan-400/25 dark:bg-cyan-400/5">
                            <div class="font-mono text-[11px] font-bold uppercase tracking-wider text-cyan-800 dark:text-cyan-300">
                                {{ __('TRANSMISSION_PROTOCOL') }}
                            </div>
                            <div class="space-y-2 text-xs text-slate-700 dark:text-zinc-300">
                                <div><span class="font-mono text-cyan-700 dark:text-cyan-400">01.</span> {{ __('Share your project goal or role') }}</div>
                                <div><span class="font-mono text-fuchsia-700 dark:text-fuchsia-400">02.</span> {{ __('Receive a response within 24 hours') }}</div>
                                <div><span class="font-mono text-amber-700 dark:text-yellow-300">03.</span> {{ __('Align on architecture & timeline') }}</div>
                            </div>
                        </div>

                        <div class="space-y-2.5 text-sm">
                            <div class="flex flex-wrap items-center justify-between gap-x-3 gap-y-1 border border-slate-200 bg-slate-50 px-4 py-2.5 dark:border-white/10 dark:bg-white/[0.03]">
                                <span class="font-mono text-xs text-slate-500 dark:text-zinc-400">{{ __('LOCATION') }}</span>
                                <span class="font-medium text-slate-900 dark:text-white">{{ $profile->location }}</span>
                            </div>

                            @if ($profile->email)
                                <div class="flex flex-wrap items-center justify-between gap-x-3 gap-y-1 border border-slate-200 bg-slate-50 px-4 py-2.5 dark:border-white/10 dark:bg-white/[0.03]">
                                    <span class="font-mono text-xs text-slate-500 dark:text-zinc-400">{{ __('EMAIL') }}</span>
                                    @if (str_contains($profile->email, '@'))
                                        <a href="mailto:{{ $profile->email }}" class="break-all font-mono text-xs font-semibold text-cyan-700 hover:underline dark:text-cyan-300">
                                            {{ $profile->email }}
                                        </a>
                                    @else
                                        <span class="break-all font-mono text-xs font-semibold text-cyan-700 select-all dark:text-cyan-300">
                                            {{ $profile->email }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <div class="flex flex-wrap gap-2 pt-2 font-mono text-xs">
                                @if ($resumeUrl)
                                    <a href="{{ $resumeUrl }}" target="_blank" class="border border-slate-300 px-3 py-1.5 text-slate-700 hover:border-cyan-600 hover:text-cyan-700 dark:border-white/15 dark:text-zinc-200 dark:hover:border-cyan-400 dark:hover:text-cyan-300">
                                        {{ __('Resume / CV') }} ↗
                                    </a>
                                @endif
                                @if ($profile->github_url)
                                    <a href="{{ $profile->github_url }}" target="_blank" class="border border-slate-300 px-3 py-1.5 text-slate-700 hover:border-cyan-600 hover:text-cyan-700 dark:border-white/15 dark:text-zinc-200 dark:hover:border-cyan-400 dark:hover:text-cyan-300">
                                        {{ __('GitHub') }} ↗
                                    </a>
                                @endif
                                @if ($profile->linkedin_url)
                                    <a href="{{ $profile->linkedin_url }}" target="_blank" class="border border-slate-300 px-3 py-1.5 text-slate-700 hover:border-cyan-600 hover:text-cyan-700 dark:border-white/15 dark:text-zinc-200 dark:hover:border-cyan-400 dark:hover:text-cyan-300">
                                        {{ __('LinkedIn') }} ↗
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="p-5 sm:p-8 lg:col-span-7 lg:p-10">
                        @if ($contactSent)
                            <div class="mb-6 border border-cyan-600/40 bg-cyan-500/10 p-4 font-mono text-xs font-semibold text-cyan-800 dark:border-cyan-400/50 dark:bg-cyan-400/10 dark:text-cyan-300">
                                [OK] {{ __('Thank you! Your message has been received and I will get back to you soon.') }}
                            </div>
                        @endif

                        <form wire:submit="submitContact" class="space-y-5">
                            <div class="grid gap-5 sm:grid-cols-2">
                                <div>
                                    <label for="contact-name" class="mb-2 block font-mono text-xs font-bold uppercase tracking-wider text-cyan-700 dark:text-cyan-300">
                                        {{ __('Your Name') }} <span class="text-fuchsia-600 dark:text-fuchsia-400">*</span>
                                    </label>
                                    <input
                                        id="contact-name"
                                        wire:model="name"
                                        type="text"
                                        required
                                        placeholder="John Doe"
                                        class="w-full border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 dark:border-white/20 dark:bg-[#06070B] dark:text-white dark:placeholder-zinc-500 dark:focus:border-cyan-400 dark:focus:ring-cyan-400"
                                    />
                                    @error('name')
                                        <p class="mt-1.5 font-mono text-xs text-fuchsia-600 dark:text-fuchsia-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="contact-email" class="mb-2 block font-mono text-xs font-bold uppercase tracking-wider text-cyan-700 dark:text-cyan-300">
                                        {{ __('Email Address') }} <span class="text-fuchsia-600 dark:text-fuchsia-400">*</span>
                                    </label>
                                    <input
                                        id="contact-email"
                                        wire:model="email"
                                        type="email"
                                        required
                                        placeholder="john@example.com"
                                        class="w-full border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 dark:border-white/20 dark:bg-[#06070B] dark:text-white dark:placeholder-zinc-500 dark:focus:border-cyan-400 dark:focus:ring-cyan-400"
                                    />
                                    @error('email')
                                        <p class="mt-1.5 font-mono text-xs text-fuchsia-600 dark:text-fuchsia-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="contact-subject" class="mb-2 block font-mono text-xs font-bold uppercase tracking-wider text-cyan-700 dark:text-cyan-300">
                                    {{ __('Subject') }}
                                </label>
                                <input
                                    id="contact-subject"
                                    wire:model="subject"
                                    type="text"
                                    placeholder="Project inquiry or collaboration"
                                    class="w-full border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 dark:border-white/20 dark:bg-[#06070B] dark:text-white dark:placeholder-zinc-500 dark:focus:border-cyan-400 dark:focus:ring-cyan-400"
                                />
                                @error('subject')
                                    <p class="mt-1.5 font-mono text-xs text-fuchsia-600 dark:text-fuchsia-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="contact-message" class="mb-2 block font-mono text-xs font-bold uppercase tracking-wider text-cyan-700 dark:text-cyan-300">
                                    {{ __('Message') }} <span class="text-fuchsia-600 dark:text-fuchsia-400">*</span>
                                </label>
                                <textarea
                                    id="contact-message"
                                    wire:model="message"
                                    rows="5"
                                    required
                                    placeholder="Tell me about your project, role, timeline, or question..."
                                    class="w-full border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 dark:border-white/20 dark:bg-[#06070B] dark:text-white dark:placeholder-zinc-500 dark:focus:border-cyan-400 dark:focus:ring-cyan-400"
                                ></textarea>
                                @error('message')
                                    <p class="mt-1.5 font-mono text-xs text-fuchsia-600 dark:text-fuchsia-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex flex-col items-start justify-between gap-3 pt-2 sm:flex-row sm:items-center">
                                <span class="font-mono text-xs text-slate-500 dark:text-zinc-400">
                                    ENCRYPTED // DIRECT INQUIRY
                                </span>

                                <button
                                    type="submit"
                                    wire:loading.attr="disabled"
                                    wire:target="submitContact"
                                    class="inline-flex w-full cursor-pointer items-center justify-center bg-cyan-500 px-6 py-3 font-mono text-xs font-black uppercase tracking-wider text-zinc-950 shadow-[0_0_24px_rgba(34,211,238,0.35)] transition hover:bg-yellow-300 disabled:opacity-60 sm:w-auto dark:bg-cyan-400"
                                    style="clip-path: polygon(0 0, calc(100% - 10px) 0, 100% 10px, 100% 100%, 10px 100%, 0 calc(100% - 10px));"
                                >
                                    {{ __('Send Message') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </main>

    <footer class="border-t border-slate-200 py-8 dark:border-white/10">
        <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-4 px-4 text-center font-mono text-xs text-slate-500 sm:flex-row sm:px-6 sm:text-left lg:px-8 dark:text-zinc-500">
            <span>© {{ date('Y') }} {{ $profile->name }} // {{ __('ALL RIGHTS RESERVED') }}</span>
            <div class="flex flex-wrap items-center justify-center gap-x-5 gap-y-2 uppercase">
                <a href="#home" class="hover:text-cyan-700 dark:hover:text-cyan-300">{{ __('Home') }}</a>
                <a href="#projects" class="hover:text-cyan-700 dark:hover:text-cyan-300">{{ __('Projects') }}</a>
                <a href="#experience" class="hover:text-cyan-700 dark:hover:text-cyan-300">{{ __('Experience') }}</a>
                <a href="#skills" class="hover:text-cyan-700 dark:hover:text-cyan-300">{{ __('Skills') }}</a>
                <a href="#home" class="text-cyan-700 hover:text-amber-600 dark:text-cyan-400 dark:hover:text-yellow-300">{{ __('Top ↑') }}</a>
            </div>
        </div>
    </footer>

    {{-- Floating Cyberpunk Back-to-Top Button --}}
    <div
        x-data="{ showTop: false }"
        x-on:scroll.window="showTop = window.scrollY > 350"
        x-show="showTop"
        x-transition.opacity.duration.200ms
        x-cloak
        class="fixed right-4 bottom-4 z-40 sm:right-6 sm:bottom-6"
    >
        <button
            type="button"
            x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            aria-label="{{ __('Back to top') }}"
            class="flex cursor-pointer items-center gap-2 border border-cyan-600 bg-white/95 px-4 py-2.5 font-mono text-xs font-black uppercase tracking-wider text-cyan-800 shadow-[0_0_25px_rgba(34,211,238,0.22)] backdrop-blur-md transition hover:-translate-y-0.5 hover:bg-cyan-500 hover:text-zinc-950 dark:border-cyan-400 dark:bg-[#06070B]/95 dark:text-cyan-300 dark:shadow-[0_0_25px_rgba(34,211,238,0.35)] dark:hover:bg-cyan-400"
            style="clip-path: polygon(0 0, calc(100% - 10px) 0, 100% 10px, 100% 100%, 10px 100%, 0 calc(100% - 10px));"
        >
            <span>↑</span>
            <span>{{ __('Top') }}</span>
        </button>
    </div>

    {{-- Project Details Modal --}}
    <flux:modal wire:model="showProjectDetailModal" class="w-[calc(100%-1.5rem)] max-w-xl !rounded-none !border !border-cyan-600/35 !bg-white !text-slate-900 shadow-2xl dark:!border-cyan-400/40 dark:!bg-[#0A0D16] dark:!text-zinc-100 dark:shadow-[0_0_60px_rgba(34,211,238,0.2)]">
        @if ($this->selectedProject)
            <div class="space-y-5">
                <div class="border-b border-slate-200 pb-4 dark:border-white/10">
                    <div class="flex flex-wrap items-center gap-2">
                        <p class="break-all font-mono text-[11px] font-bold uppercase tracking-widest text-cyan-700 dark:text-cyan-400">
                            PROJECT_SPECS // {{ $this->selectedProject->slug }}
                        </p>
                        @if ($this->selectedProject->is_featured)
                            <span class="bg-yellow-300 px-2 py-0.5 font-mono text-[10px] font-black uppercase text-zinc-950">
                                {{ __('Featured') }}
                            </span>
                        @endif
                    </div>
                    <h3 class="mt-1.5 break-words text-xl font-black tracking-tight text-slate-900 sm:text-2xl dark:text-white">
                        {{ $this->selectedProject->title }}
                    </h3>
                </div>

                <div class="h-44 w-full overflow-hidden border border-slate-200 bg-slate-100 sm:h-52 dark:border-white/15 dark:bg-zinc-950">
                    @if ($this->selectedProject->thumbnailUrl())
                        <img
                            src="{{ $this->selectedProject->thumbnailUrl() }}"
                            alt="{{ $this->selectedProject->title }}"
                            class="h-full w-full object-cover object-top"
                        />
                    @else
                        <div class="flex h-full flex-col items-center justify-center bg-[radial-gradient(circle_at_center,rgba(34,211,238,0.08),transparent_70%)] p-4 text-center">
                            <div class="border border-cyan-600/30 bg-cyan-500/10 px-3 py-1 font-mono text-[11px] font-bold tracking-widest text-cyan-800 uppercase dark:border-cyan-400/30 dark:bg-cyan-400/5 dark:text-cyan-300">
                                PROJECT_SPECS
                            </div>
                            <div class="mt-2 font-mono text-[10px] text-slate-500 dark:text-zinc-500">
                                {{ __('NO PREVIEW IMAGE') }}
                            </div>
                        </div>
                    @endif
                </div>

                <p class="text-sm leading-relaxed text-slate-600 dark:text-zinc-300">
                    {{ $this->selectedProject->description }}
                </p>

                <div class="flex flex-wrap gap-1.5">
                    @foreach ($this->selectedProject->tech_stack as $tech)
                        <span class="border border-cyan-600/25 bg-cyan-50 px-2.5 py-1 font-mono text-[11px] font-semibold text-cyan-800 dark:border-cyan-400/25 dark:bg-cyan-400/5 dark:text-cyan-200">
                            {{ $tech }}
                        </span>
                    @endforeach
                </div>

                <div class="flex justify-end border-t border-slate-200 pt-4 dark:border-white/10">
                    <button
                        type="button"
                        wire:click="$set('showProjectDetailModal', false)"
                        class="cursor-pointer border border-cyan-600/40 bg-cyan-500/10 px-5 py-2 font-mono text-xs font-bold uppercase tracking-wider text-cyan-800 transition hover:bg-cyan-500 hover:text-zinc-950 dark:border-cyan-400/50 dark:bg-cyan-400/10 dark:text-cyan-300 dark:hover:bg-cyan-400"
                    >
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        @endif
    </flux:modal>

    {{-- All Projects Modal --}}
    <flux:modal wire:model="showAllProjectsModal" class="w-[calc(100%-1.5rem)] max-w-5xl !rounded-none !border !border-cyan-600/35 !bg-white !text-slate-900 shadow-2xl dark:!border-cyan-400/40 dark:!bg-[#0A0D16] dark:!text-zinc-100 dark:shadow-[0_0_60px_rgba(34,211,238,0.2)]">
        <div
            x-data="{
                detail: null,
                openDetail(item) {
                    this.detail = item;
                    this.$nextTick(() => {
                        if (! this.$refs.detailDialog.open) {
                            this.$refs.detailDialog.showModal();
                        }
                    });
                },
                closeDetail() {
                    if (this.$refs.detailDialog.open) {
                        this.$refs.detailDialog.close();
                    }
                    this.detail = null;
                }
            }"
            class="space-y-6"
        >
            {{-- Native Top-Layer Detail Dialog (edge-to-edge backdrop, centered, locks outer scroll) --}}
            <dialog
                x-ref="detailDialog"
                x-on:click.self="closeDetail()"
                x-on:cancel.prevent="closeDetail()"
                class="m-auto w-[calc(100%-1.5rem)] max-w-xl bg-transparent p-0 text-slate-900 outline-none backdrop:bg-black/70 backdrop:backdrop-blur-xs sm:w-[calc(100%-2rem)] dark:text-zinc-100 dark:backdrop:bg-black/80"
            >
                <div class="max-h-[90vh] w-full space-y-5 overflow-y-auto border border-cyan-600/40 bg-white p-4 shadow-2xl sm:p-6 dark:border-cyan-400/50 dark:bg-[#0A0D16] dark:shadow-[0_0_60px_rgba(34,211,238,0.28)]">
                    <div class="flex items-start justify-between gap-3 border-b border-slate-200 pb-4 dark:border-white/10">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="break-all font-mono text-[11px] font-bold uppercase tracking-widest text-cyan-700 dark:text-cyan-400" x-text="'PROJECT_SPECS // ' + (detail?.slug ?? '')"></p>
                                <span x-show="detail?.is_featured" class="bg-yellow-300 px-2 py-0.5 font-mono text-[10px] font-black uppercase text-zinc-950">
                                    {{ __('Featured') }}
                                </span>
                            </div>
                            <h3 class="mt-1.5 break-words text-xl font-black tracking-tight text-slate-900 sm:text-2xl dark:text-white" x-text="detail?.title ?? ''"></h3>
                        </div>

                        <button
                            type="button"
                            x-on:click="closeDetail()"
                            aria-label="{{ __('Close') }}"
                            class="shrink-0 cursor-pointer p-1 text-slate-500 transition hover:text-slate-900 dark:text-zinc-400 dark:hover:text-white"
                        >
                            ✕
                        </button>
                    </div>

                    <div class="h-44 w-full overflow-hidden border border-slate-200 bg-slate-100 sm:h-52 dark:border-white/15 dark:bg-zinc-950">
                        <img
                            x-show="detail?.thumbnail_url"
                            :src="detail?.thumbnail_url ?? ''"
                            :alt="detail?.title ?? ''"
                            class="h-full w-full object-cover object-top"
                        />
                        <div
                            x-show="! detail?.thumbnail_url"
                            class="flex h-full flex-col items-center justify-center bg-[radial-gradient(circle_at_center,rgba(34,211,238,0.08),transparent_70%)] p-4 text-center"
                        >
                            <div class="border border-cyan-600/30 bg-cyan-500/10 px-3 py-1 font-mono text-[11px] font-bold tracking-widest text-cyan-800 uppercase dark:border-cyan-400/30 dark:bg-cyan-400/5 dark:text-cyan-300">
                                PROJECT_SPECS
                            </div>
                            <div class="mt-2 font-mono text-[10px] text-slate-500 dark:text-zinc-500">
                                {{ __('NO PREVIEW IMAGE') }}
                            </div>
                        </div>
                    </div>

                    <p class="text-sm leading-relaxed text-slate-600 dark:text-zinc-300" x-text="detail?.description ?? ''"></p>

                    <div class="flex flex-wrap gap-1.5">
                        <template x-for="tech in (detail?.tech_stack ?? [])" :key="tech">
                            <span class="border border-cyan-600/25 bg-cyan-50 px-2.5 py-1 font-mono text-[11px] font-semibold text-cyan-800 dark:border-cyan-400/25 dark:bg-cyan-400/5 dark:text-cyan-200" x-text="tech"></span>
                        </template>
                    </div>

                    <div class="flex justify-end border-t border-slate-200 pt-4 dark:border-white/10">
                        <button
                            type="button"
                            x-on:click="closeDetail()"
                            class="cursor-pointer border border-cyan-600/40 bg-cyan-500/10 px-5 py-2 font-mono text-xs font-bold uppercase tracking-wider text-cyan-800 transition hover:bg-cyan-500 hover:text-zinc-950 dark:border-cyan-400/50 dark:bg-cyan-400/10 dark:text-cyan-300 dark:hover:bg-cyan-400"
                        >
                            {{ __('Close') }}
                        </button>
                    </div>
                </div>
            </dialog>

            <div class="space-y-6">
                <div class="border-b border-slate-200 pb-4 dark:border-white/10">
                    <p class="font-mono text-[11px] font-bold uppercase tracking-widest text-cyan-700 dark:text-cyan-400">
                        ARCHIVE // {{ __('DEPLOYED_SYSTEMS') }}
                    </p>
                    <h3 class="mt-1 text-2xl font-black tracking-tight text-slate-900 dark:text-white">
                        {{ __('All Projects') }} ({{ $totalProjects }})
                    </h3>
                    <p class="mt-1 text-sm text-slate-600 dark:text-zinc-400">
                        {{ __('Complete catalog of builds, systems, and web applications.') }}
                    </p>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    @foreach ($this->projects as $project)
                        @php
                            $projectPayload = [
                                'title' => $project->title,
                                'slug' => $project->slug,
                                'description' => $project->description,
                                'tech_stack' => $project->tech_stack,
                                'thumbnail_url' => $project->thumbnailUrl(),
                                'is_featured' => $project->is_featured,
                            ];
                        @endphp
                        <div
                            wire:key="modal-project-{{ $project->id }}"
                            class="flex flex-col overflow-hidden border border-slate-200 bg-slate-50 transition hover:border-cyan-500/60 dark:border-white/15 dark:bg-[#06070B] dark:hover:border-cyan-400/60"
                        >
                            <div class="relative h-40 w-full overflow-hidden border-b border-slate-200 bg-slate-100 dark:border-white/15 dark:bg-zinc-950">
                                @if ($project->is_featured)
                                    <span class="absolute top-2.5 right-2.5 z-10 bg-yellow-300 px-2 py-0.5 font-mono text-[10px] font-black uppercase text-zinc-950">
                                        {{ __('Featured') }}
                                    </span>
                                @endif

                                @if ($project->thumbnailUrl())
                                    <img
                                        src="{{ $project->thumbnailUrl() }}"
                                        alt="{{ $project->title }}"
                                        class="h-full w-full object-cover object-top"
                                    />
                                @else
                                    <div class="flex h-full flex-col items-center justify-center bg-[radial-gradient(circle_at_center,rgba(34,211,238,0.08),transparent_70%)] p-4 text-center">
                                        <div class="border border-cyan-600/30 bg-cyan-500/10 px-2.5 py-0.5 font-mono text-[10px] font-bold tracking-widest text-cyan-800 uppercase dark:border-cyan-400/30 dark:bg-cyan-400/5 dark:text-cyan-300">
                                            SYS_BUILD // 0{{ $loop->iteration }}
                                        </div>
                                        <div class="mt-2 font-mono text-[10px] text-slate-500 dark:text-zinc-500">
                                            {{ __('NO PREVIEW IMAGE') }}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-1 flex-col justify-between p-5 text-center">
                                <div>
                                    <h4 class="text-base font-black uppercase tracking-wide text-slate-900 dark:text-white">{{ $project->title }}</h4>

                                    <div class="mt-3 flex flex-wrap justify-center gap-1.5">
                                        @foreach (array_slice($project->tech_stack, 0, 4) as $tech)
                                            <span class="border border-cyan-600/20 bg-cyan-50 px-2 py-0.5 font-mono text-[11px] text-cyan-800 dark:border-cyan-400/20 dark:bg-cyan-400/5 dark:text-cyan-200">
                                                {{ $tech }}
                                            </span>
                                        @endforeach
                                        @if (count($project->tech_stack) > 4)
                                            <span class="border border-fuchsia-600/30 bg-fuchsia-50 px-2 py-0.5 font-mono text-[11px] font-bold text-fuchsia-700 dark:border-fuchsia-500/30 dark:bg-fuchsia-500/10 dark:text-fuchsia-300">
                                                +{{ count($project->tech_stack) - 4 }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-4 flex flex-wrap items-center justify-between gap-2 border-t border-slate-200 pt-3 font-mono text-xs font-bold uppercase dark:border-white/10">
                                    <div class="flex items-center gap-3">
                                        @if ($project->demo_url)
                                            <a href="{{ $project->demo_url }}" target="_blank" class="text-cyan-700 hover:text-cyan-600 dark:text-cyan-400 dark:hover:text-cyan-300">
                                                {{ __('Demo →') }}
                                            </a>
                                        @endif

                                        @if ($project->repo_url)
                                            <a href="{{ $project->repo_url }}" target="_blank" class="text-fuchsia-700 hover:text-fuchsia-600 dark:text-fuchsia-400 dark:hover:text-fuchsia-300">
                                                {{ __('Repository →') }}
                                            </a>
                                        @endif
                                    </div>

                                    <button
                                        type="button"
                                        x-on:click="openDetail({{ \Illuminate\Support\Js::from($projectPayload) }})"
                                        class="cursor-pointer border border-cyan-600/35 bg-cyan-50 px-2.5 py-1 text-[11px] text-cyan-800 transition hover:bg-cyan-500 hover:text-zinc-950 dark:border-cyan-400/40 dark:bg-cyan-400/5 dark:text-cyan-300 dark:hover:bg-cyan-400"
                                    >
                                        {{ __('Show Details') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end border-t border-slate-200 pt-4 dark:border-white/10">
                    <button
                        type="button"
                        wire:click="$set('showAllProjectsModal', false)"
                        class="cursor-pointer border border-cyan-600/40 bg-cyan-500/10 px-5 py-2 font-mono text-xs font-bold uppercase tracking-wider text-cyan-800 transition hover:bg-cyan-500 hover:text-zinc-950 dark:border-cyan-400/50 dark:bg-cyan-400/10 dark:text-cyan-300 dark:hover:bg-cyan-400"
                    >
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </flux:modal>
</div>
