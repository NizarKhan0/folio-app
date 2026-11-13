@php
    $profile = $this->profile;
    $bannerUrl = $profile->bannerUrl();
    $avatarUrl = $profile->avatarUrl();
    $resumeUrl = $profile->resolvedResumeUrl();
    $featuredProjects = $this->projects->take(6);
    $totalProjects = $this->projects->count();
    $filteredSkills = $skillCategoryFilter === 'all'
        ? $this->skills
        : $this->skills->where('category', $skillCategoryFilter);
@endphp

<div class="relative min-h-screen bg-zinc-950 text-zinc-100 selection:bg-emerald-500/30 selection:text-emerald-200">
    {{-- Floating Glass Navbar --}}
    <header class="sticky top-0 z-50 border-b border-white/10 bg-zinc-950/75 backdrop-blur-xl">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3.5 sm:px-6 lg:px-8">
            <a href="#home" class="group flex items-center gap-2.5 text-sm font-semibold tracking-tight text-white">
                <span class="flex size-8 items-center justify-center rounded-lg bg-gradient-to-br from-emerald-400 to-cyan-500 text-xs font-bold text-zinc-950 shadow-sm shadow-emerald-500/20 transition group-hover:scale-105">
                    {{ \Illuminate\Support\Str::initials($profile->name ?: 'NK', true) }}
                </span>
                <span>{{ $profile->name }}</span>
            </a>

            <nav class="hidden items-center gap-1 rounded-full border border-white/10 bg-white/5 px-2 py-1 text-xs font-medium text-zinc-300 md:flex">
                <a href="#home" class="rounded-full px-3 py-1.5 transition hover:bg-white/10 hover:text-white">{{ __('Home') }}</a>
                <a href="#projects" class="rounded-full px-3 py-1.5 transition hover:bg-white/10 hover:text-white">{{ __('Projects') }}</a>
                <a href="#experience" class="rounded-full px-3 py-1.5 transition hover:bg-white/10 hover:text-white">{{ __('Experience') }}</a>
                <a href="#skills" class="rounded-full px-3 py-1.5 transition hover:bg-white/10 hover:text-white">{{ __('Skills') }}</a>
                <a href="#contact" class="rounded-full px-3 py-1.5 transition hover:bg-white/10 hover:text-white">{{ __('Contact') }}</a>
            </nav>

            <div class="flex items-center gap-2.5">
                @auth
                    <flux:button size="sm" variant="primary" :href="route('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:button>
                @else
                    <a
                        href="#contact"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3.5 py-2 text-xs font-semibold text-zinc-950 transition hover:bg-zinc-200"
                    >
                        {{ __('Contact Me') }}
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Hero Section --}}
    <section id="home" class="relative overflow-hidden border-b border-white/10">
        @if ($bannerUrl)
            <div
                class="absolute inset-0 bg-cover bg-center opacity-40"
                style="background-image: url('{{ $bannerUrl }}');"
            ></div>
            <div class="absolute inset-0 bg-gradient-to-b from-zinc-950/80 via-zinc-950/85 to-zinc-950"></div>
        @else
            <div class="pointer-events-none absolute -top-32 left-1/2 h-[480px] w-[720px] -translate-x-1/2 rounded-full bg-emerald-500/15 blur-[130px]"></div>
            <div class="pointer-events-none absolute top-24 right-0 h-80 w-80 rounded-full bg-cyan-500/10 blur-[110px]"></div>
            <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.035)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.035)_1px,transparent_1px)] bg-[size:36px_36px]"></div>
        @endif

        <div class="relative mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
            <div class="grid items-center gap-12 lg:grid-cols-12">
                <div class="space-y-6 lg:col-span-7">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="inline-flex items-center gap-2 rounded-full border border-emerald-500/30 bg-emerald-500/10 px-3 py-1 text-xs font-medium text-emerald-300">
                            @if ($profile->is_available)
                                <span class="relative flex size-2">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex size-2 rounded-full bg-emerald-400"></span>
                                </span>
                                {{ __('Available for Work') }}
                            @else
                                <span class="size-2 rounded-full bg-zinc-400"></span>
                                {{ __('Currently Unavailable') }}
                            @endif
                        </span>

                        <span class="inline-flex items-center gap-1.5 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-zinc-300">
                            {{ $profile->location }}
                        </span>
                    </div>

                    <h1 class="bg-gradient-to-br from-white via-zinc-100 to-zinc-400 bg-clip-text text-4xl font-bold tracking-tight text-transparent sm:text-5xl lg:text-6xl">
                        {{ $profile->headline }}
                    </h1>

                    <p class="text-lg font-medium text-emerald-300/90 sm:text-xl">
                        {{ $profile->tagline }}
                    </p>

                    @if ($profile->bio)
                        <p class="max-w-2xl text-base leading-relaxed text-zinc-400">
                            {{ $profile->bio }}
                        </p>
                    @endif

                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <a
                            href="#contact"
                            class="inline-flex items-center justify-center rounded-xl bg-emerald-400 px-5 py-2.5 text-sm font-semibold text-zinc-950 shadow-lg shadow-emerald-500/20 transition hover:bg-emerald-300"
                        >
                            {{ __('Contact Me') }}
                        </a>

                        <a
                            href="#projects"
                            class="inline-flex items-center justify-center rounded-xl border border-white/15 bg-white/5 px-5 py-2.5 text-sm font-semibold text-white transition hover:border-white/30 hover:bg-white/10"
                        >
                            {{ __('View Projects') }}
                        </a>

                        @if ($resumeUrl)
                            <a
                                href="{{ $resumeUrl }}"
                                target="_blank"
                                class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-medium text-zinc-300 transition hover:bg-white/5 hover:text-white"
                            >
                                {{ __('Resume / CV') }} ↗
                            </a>
                        @endif

                        @if ($profile->github_url)
                            <a
                                href="{{ $profile->github_url }}"
                                target="_blank"
                                class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-medium text-zinc-300 transition hover:bg-white/5 hover:text-white"
                            >
                                {{ __('GitHub') }} ↗
                            </a>
                        @endif

                        @if ($profile->linkedin_url)
                            <a
                                href="{{ $profile->linkedin_url }}"
                                target="_blank"
                                class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-medium text-zinc-300 transition hover:bg-white/5 hover:text-white"
                            >
                                {{ __('LinkedIn') }} ↗
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Right Column: Prominent Portrait + Developer Snapshot --}}
                <div class="space-y-4 lg:col-span-5">
                    <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-zinc-900/80 p-5 shadow-2xl backdrop-blur-md">
                        <div class="flex flex-col items-center gap-5 sm:flex-row sm:items-center">
                            @if ($avatarUrl)
                                <div class="relative shrink-0">
                                    <div class="absolute -inset-1 rounded-2xl bg-gradient-to-br from-emerald-400/40 to-cyan-500/40 blur-sm"></div>
                                    <img
                                        src="{{ $avatarUrl }}"
                                        alt="{{ $profile->name }}"
                                        class="relative size-28 rounded-2xl border border-white/20 object-cover shadow-xl sm:size-32"
                                    />
                                </div>
                            @else
                                <div class="flex size-28 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400/20 to-cyan-500/20 text-3xl font-bold text-emerald-300 ring-1 ring-emerald-400/30 sm:size-32">
                                    {{ \Illuminate\Support\Str::initials($profile->name ?: 'NK', true) }}
                                </div>
                            @endif

                            <div class="space-y-2 text-center sm:text-left">
                                <div class="inline-flex items-center gap-1.5 rounded-full border border-white/10 bg-white/5 px-2.5 py-0.5 font-mono text-[11px] text-emerald-300">
                                    <span>&lt;/&gt;</span> {{ __('Fullstack Engineer') }}
                                </div>
                                <h2 class="text-xl font-bold text-white">{{ $profile->name }}</h2>
                                <p class="text-xs text-zinc-400">{{ $profile->location }}</p>
                            </div>
                        </div>

                        <div class="mt-5 rounded-xl border border-white/5 bg-zinc-950/90 p-4 font-mono text-xs leading-relaxed text-zinc-300">
                            <div class="mb-2 flex items-center justify-between border-b border-white/5 pb-2">
                                <div class="flex items-center gap-1.5">
                                    <span class="size-2 rounded-full bg-rose-500/80"></span>
                                    <span class="size-2 rounded-full bg-amber-400/80"></span>
                                    <span class="size-2 rounded-full bg-emerald-400/80"></span>
                                </div>
                                <span class="text-[10px] text-zinc-500">profile.json</span>
                            </div>
                            <div><span class="text-emerald-400">const</span> developer = &#123;</div>
                            <div class="pl-4"><span class="text-zinc-500">focus:</span> <span class="text-amber-300">"Laravel &amp; Livewire"</span>,</div>
                            <div class="pl-4"><span class="text-zinc-500">status:</span> <span class="text-emerald-300">"{{ $profile->is_available ? 'open_to_work' : 'busy' }}"</span></div>
                            <div>&#125;;</div>
                        </div>

                        <div class="mt-4 grid grid-cols-3 gap-2 text-center">
                            <div class="rounded-xl border border-white/5 bg-white/[0.03] p-3">
                                <div class="text-lg font-bold text-white">{{ $totalProjects }}</div>
                                <div class="text-[11px] text-zinc-400">{{ __('Projects') }}</div>
                            </div>
                            <div class="rounded-xl border border-white/5 bg-white/[0.03] p-3">
                                <div class="text-lg font-bold text-white">{{ $this->experiences->count() }}</div>
                                <div class="text-[11px] text-zinc-400">{{ __('Roles') }}</div>
                            </div>
                            <div class="rounded-xl border border-white/5 bg-white/[0.03] p-3">
                                <div class="text-lg font-bold text-white">{{ $this->skills->count() }}</div>
                                <div class="text-[11px] text-zinc-400">{{ __('Skills') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Projects Section (Top 6 + View All Modal) --}}
    <section id="projects" class="mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="mb-12 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="font-mono text-xs font-semibold uppercase tracking-widest text-emerald-400">
                    01 // {{ __('Selected Work') }}
                </p>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">
                    {{ __('Projects') }}
                </h2>
            </div>

            <div class="flex flex-wrap items-center gap-4">
                <p class="max-w-sm text-sm text-zinc-400">
                    {{ __('Showing latest :count of :total published projects.', ['count' => $featuredProjects->count(), 'total' => $totalProjects]) }}
                </p>

                @if ($totalProjects > 0)
                    <button
                        type="button"
                        wire:click="$set('showAllProjectsModal', true)"
                        class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-emerald-400/30 bg-emerald-400/10 px-4 py-2 text-xs font-semibold text-emerald-300 transition hover:bg-emerald-400/20"
                    >
                        {{ __('View All Projects') }} ({{ $totalProjects }}) →
                    </button>
                @endif
            </div>
        </div>

        @if ($featuredProjects->isEmpty())
            <div class="rounded-2xl border border-dashed border-white/15 p-12 text-center text-sm text-zinc-400">
                {{ __('Projects will be published soon.') }}
            </div>
        @else
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredProjects as $project)
                    <article
                        wire:key="public-project-{{ $project->id }}"
                        class="group flex flex-col overflow-hidden rounded-2xl border border-white/10 bg-zinc-900/60 transition duration-300 hover:-translate-y-1 hover:border-emerald-400/40 hover:bg-zinc-900"
                    >
                        @if ($project->thumbnailUrl())
                            <div class="relative h-48 overflow-hidden border-b border-white/10">
                                <img
                                    src="{{ $project->thumbnailUrl() }}"
                                    alt="{{ $project->title }}"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                />
                            </div>
                        @else
                            <div class="relative flex h-40 items-center justify-center overflow-hidden border-b border-white/10 bg-gradient-to-br from-zinc-900 via-zinc-950 to-emerald-950/30 px-6">
                                <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:20px_20px]"></div>
                                <span class="relative text-center font-mono text-xs uppercase tracking-widest text-zinc-500">
                                    /{{ $project->slug }}
                                </span>
                            </div>
                        @endif

                        <div class="flex flex-1 flex-col p-6">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-lg font-semibold text-white transition group-hover:text-emerald-300">
                                    {{ $project->title }}
                                </h3>
                                @if ($project->is_featured)
                                    <span class="shrink-0 rounded-full border border-amber-400/30 bg-amber-400/10 px-2.5 py-0.5 text-[11px] font-medium text-amber-300">
                                        {{ __('Featured') }}
                                    </span>
                                @endif
                            </div>

                            <p class="mt-3 flex-1 text-sm leading-relaxed text-zinc-400">
                                {{ $project->description }}
                            </p>

                            <div class="mt-5 flex flex-wrap gap-1.5">
                                @foreach ($project->tech_stack as $tech)
                                    <span class="rounded-md border border-white/10 bg-white/5 px-2 py-0.5 text-[11px] font-medium text-zinc-300">
                                        {{ $tech }}
                                    </span>
                                @endforeach
                            </div>

                            @if ($project->demo_url || $project->repo_url)
                                <div class="mt-5 flex items-center gap-4 border-t border-white/10 pt-4 text-xs font-semibold">
                                    @if ($project->demo_url)
                                        <a
                                            href="{{ $project->demo_url }}"
                                            target="_blank"
                                            class="text-emerald-400 transition hover:text-emerald-300"
                                        >
                                            {{ __('Demo →') }}
                                        </a>
                                    @endif

                                    @if ($project->repo_url)
                                        <a
                                            href="{{ $project->repo_url }}"
                                            target="_blank"
                                            class="text-zinc-400 transition hover:text-white"
                                        >
                                            {{ __('Repository →') }}
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Experience Section (Modern Career Journey Infographic) --}}
    <section id="experience" class="border-y border-white/10 bg-zinc-900/30">
        <div class="mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="mb-12 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="font-mono text-xs font-semibold uppercase tracking-widest text-emerald-400">
                        02 // {{ __('Career Journey') }}
                    </p>
                    <h2 class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">
                        {{ __('Experience') }}
                    </h2>
                </div>

                {{-- Infographic Career Summary Strip --}}
                <div class="grid grid-cols-3 gap-3">
                    <div class="rounded-xl border border-white/10 bg-zinc-950/80 px-4 py-3">
                        <div class="font-mono text-[10px] uppercase tracking-wider text-zinc-500">{{ __('Milestones') }}</div>
                        <div class="mt-1 text-lg font-bold text-white">{{ $this->experiences->count() }} {{ __('Roles') }}</div>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-zinc-950/80 px-4 py-3">
                        <div class="font-mono text-[10px] uppercase tracking-wider text-zinc-500">{{ __('Current') }}</div>
                        <div class="mt-1 truncate text-sm font-semibold text-emerald-300">
                            {{ $this->experiences->first()?->role ?? 'Developer' }}
                        </div>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-zinc-950/80 px-4 py-3">
                        <div class="font-mono text-[10px] uppercase tracking-wider text-zinc-500">{{ __('Focus') }}</div>
                        <div class="mt-1 text-sm font-semibold text-cyan-300">Laravel / DevOps</div>
                    </div>
                </div>
            </div>

            {{-- Infographic Stepped Roadmap --}}
            <div class="relative space-y-8 before:absolute before:top-6 before:bottom-6 before:left-6 before:hidden before:w-0.5 before:bg-gradient-to-b before:from-emerald-400 before:via-cyan-500/60 before:to-zinc-800 md:before:block">
                @foreach ($this->experiences as $experience)
                    <article
                        wire:key="public-experience-{{ $experience->id }}"
                        class="group relative md:pl-16"
                    >
                        {{-- Infographic Node --}}
                        <div class="mb-3 flex items-center gap-3 md:mb-0">
                            <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl border border-emerald-400/40 bg-zinc-950 text-base font-bold text-emerald-300 shadow-lg shadow-emerald-500/10 ring-4 ring-zinc-950 transition group-hover:scale-105 group-hover:border-emerald-300 md:absolute md:top-6 md:left-0">
                                {{ $experience->companyInitial() }}
                            </div>

                            <span class="font-mono text-xs font-semibold text-emerald-400 md:hidden">
                                {{ __('STEP') }} {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-zinc-950/90 p-6 transition duration-300 group-hover:border-emerald-400/30">
                            <div class="flex flex-col gap-3 border-b border-white/5 pb-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="space-y-1">
                                    <div class="flex flex-wrap items-center gap-2.5">
                                        <span class="hidden rounded-md bg-emerald-400/10 px-2 py-0.5 font-mono text-[11px] font-semibold text-emerald-300 md:inline-block">
                                            {{ __('STEP') }} {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <h3 class="text-lg font-bold text-white">
                                            {{ $experience->role }}
                                        </h3>
                                    </div>

                                    <div class="text-sm font-medium text-zinc-300">
                                        {{ $experience->company }}
                                        @if ($experience->location)
                                            <span class="text-zinc-600">•</span>
                                            <span class="text-zinc-400">{{ $experience->location }}</span>
                                        @endif
                                    </div>
                                </div>

                                <span class="inline-flex w-fit shrink-0 items-center gap-1.5 rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1 font-mono text-xs font-medium text-emerald-300">
                                    {{ $experience->period }}
                                </span>
                            </div>

                            <p class="mt-4 text-sm leading-relaxed text-zinc-400">
                                {{ $experience->description }}
                            </p>

                            @if (! empty($experience->tech_stack))
                                <div class="mt-5 flex flex-wrap items-center gap-1.5 pt-1">
                                    <span class="mr-1 font-mono text-[10px] uppercase tracking-wider text-zinc-500">
                                        {{ __('Stack:') }}
                                    </span>
                                    @foreach ($experience->tech_stack as $tech)
                                        <span class="rounded-md border border-white/10 bg-white/5 px-2.5 py-0.5 text-[11px] font-medium text-zinc-300">
                                            {{ $tech }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Skills Section (Interactive Filter + Visual Category Bento) --}}
    <section id="skills" class="mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="mb-10 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="font-mono text-xs font-semibold uppercase tracking-widest text-emerald-400">
                    03 // {{ __('Tech Arsenal') }}
                </p>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">
                    {{ __('Skills') }}
                </h2>
            </div>

            {{-- Interactive Category Filter Pills --}}
            <div class="flex flex-wrap gap-2">
                <button
                    type="button"
                    wire:click="$set('skillCategoryFilter', 'all')"
                    @class([
                        'cursor-pointer rounded-full px-3.5 py-1.5 text-xs font-semibold transition',
                        'bg-emerald-400 text-zinc-950' => $skillCategoryFilter === 'all',
                        'border border-white/10 bg-white/5 text-zinc-300 hover:bg-white/10' => $skillCategoryFilter !== 'all',
                    ])
                >
                    {{ __('All') }} ({{ $this->skills->count() }})
                </button>

                @foreach (\App\Models\Skill::CATEGORIES as $categoryKey => $categoryLabel)
                    <button
                        type="button"
                        wire:click="$set('skillCategoryFilter', '{{ $categoryKey }}')"
                        @class([
                            'cursor-pointer rounded-full px-3.5 py-1.5 text-xs font-semibold transition',
                            'bg-emerald-400 text-zinc-950' => $skillCategoryFilter === $categoryKey,
                            'border border-white/10 bg-white/5 text-zinc-300 hover:bg-white/10' => $skillCategoryFilter !== $categoryKey,
                        ])
                    >
                        {{ __($categoryLabel) }}
                    </button>
                @endforeach
            </div>
        </div>

        @if ($skillCategoryFilter === 'all')
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach (\App\Models\Skill::CATEGORIES as $categoryKey => $categoryLabel)
                    @php
                        $categorySkills = $this->skills->where('category', $categoryKey);
                        $percentage = $this->skills->count() > 0
                            ? (int) round(($categorySkills->count() / $this->skills->count()) * 100)
                            : 0;
                    @endphp
                    @if ($categorySkills->isNotEmpty())
                        <div class="group rounded-2xl border border-white/10 bg-zinc-900/60 p-6 transition hover:border-emerald-400/30">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-white">
                                    {{ __($categoryLabel) }}
                                </h3>
                                <span class="rounded-md bg-white/5 px-2 py-0.5 font-mono text-xs text-emerald-300">
                                    {{ $categorySkills->count() }}
                                </span>
                            </div>

                            <div class="mt-3 h-1 w-full overflow-hidden rounded-full bg-white/10">
                                <div
                                    class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-cyan-400"
                                    style="width: {{ max($percentage, 15) }}%;"
                                ></div>
                            </div>

                            <div class="mt-5 flex flex-wrap gap-2">
                                @foreach ($categorySkills as $skill)
                                    <span
                                        wire:key="public-skill-{{ $skill->id }}"
                                        class="rounded-lg border border-white/10 bg-zinc-950/80 px-3 py-1.5 text-xs font-medium text-zinc-200 transition hover:border-emerald-400/50 hover:bg-emerald-400/10 hover:text-emerald-300"
                                    >
                                        {{ $skill->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="rounded-2xl border border-white/10 bg-zinc-900/60 p-6 sm:p-8">
                <div class="flex flex-wrap gap-3">
                    @foreach ($filteredSkills as $skill)
                        <span
                            wire:key="public-skill-filtered-{{ $skill->id }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-zinc-950/90 px-4 py-2.5 text-sm font-medium text-zinc-100 transition hover:border-emerald-400/50 hover:text-emerald-300"
                        >
                            <span class="size-1.5 rounded-full bg-emerald-400"></span>
                            {{ $skill->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif
    </section>

    {{-- Contact Section (Executive Split Card) --}}
    <section id="contact" class="relative overflow-hidden border-t border-white/10 bg-zinc-900/30">
        <div class="pointer-events-none absolute -bottom-32 left-1/2 h-80 w-[600px] -translate-x-1/2 rounded-full bg-emerald-500/10 blur-[120px]"></div>

        <div class="relative mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl border border-white/10 bg-zinc-950/90 shadow-2xl">
                <div class="grid lg:grid-cols-12">
                    <div class="flex flex-col justify-between gap-8 border-b border-white/10 bg-gradient-to-br from-zinc-900 via-zinc-950 to-emerald-950/20 p-8 sm:p-10 lg:col-span-5 lg:border-r lg:border-b-0">
                        <div class="space-y-4">
                            <p class="font-mono text-xs font-semibold uppercase tracking-widest text-emerald-400">
                                04 // {{ __("Let's Connect") }}
                            </p>
                            <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                                {{ __('Get In Touch') }}
                            </h2>
                            <p class="text-sm leading-relaxed text-zinc-400">
                                {{ __('Have a project, full-time opportunity, or technical consultation in mind? Send a message and I will respond within 24 hours.') }}
                            </p>
                        </div>

                        <div class="space-y-3 text-sm">
                            <div class="flex items-center justify-between rounded-xl border border-white/10 bg-white/[0.03] px-4 py-3">
                                <span class="text-zinc-500">{{ __('Availability') }}</span>
                                <span class="inline-flex items-center gap-2 font-medium text-emerald-300">
                                    <span class="size-2 rounded-full {{ $profile->is_available ? 'bg-emerald-400' : 'bg-zinc-500' }}"></span>
                                    {{ $profile->is_available ? __('Available for Work') : __('Currently Unavailable') }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between rounded-xl border border-white/10 bg-white/[0.03] px-4 py-3">
                                <span class="text-zinc-500">{{ __('Location') }}</span>
                                <span class="font-medium text-zinc-200">{{ $profile->location }}</span>
                            </div>

                            @if ($profile->email)
                                <div class="flex items-center justify-between rounded-xl border border-white/10 bg-white/[0.03] px-4 py-3">
                                    <span class="text-zinc-500">{{ __('Email') }}</span>
                                    <a href="mailto:{{ $profile->email }}" class="font-medium text-emerald-400 hover:underline">
                                        {{ $profile->email }}
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-wrap gap-2 pt-2">
                            @if ($resumeUrl)
                                <a
                                    href="{{ $resumeUrl }}"
                                    target="_blank"
                                    class="rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-medium text-zinc-300 transition hover:bg-white/10 hover:text-white"
                                >
                                    {{ __('Resume / CV') }} ↗
                                </a>
                            @endif

                            @if ($profile->github_url)
                                <a
                                    href="{{ $profile->github_url }}"
                                    target="_blank"
                                    class="rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-medium text-zinc-300 transition hover:bg-white/10 hover:text-white"
                                >
                                    {{ __('GitHub') }} ↗
                                </a>
                            @endif

                            @if ($profile->linkedin_url)
                                <a
                                    href="{{ $profile->linkedin_url }}"
                                    target="_blank"
                                    class="rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-medium text-zinc-300 transition hover:bg-white/10 hover:text-white"
                                >
                                    {{ __('LinkedIn') }} ↗
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="p-8 sm:p-10 lg:col-span-7">
                        @if ($contactSent)
                            <div class="mb-6 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4 text-sm font-medium text-emerald-300">
                                {{ __('Thank you! Your message has been received and I will get back to you soon.') }}
                            </div>
                        @endif

                        <form wire:submit="submitContact" class="space-y-5">
                            <div class="grid gap-5 sm:grid-cols-2">
                                <flux:input
                                    wire:model="name"
                                    :label="__('Your Name')"
                                    type="text"
                                    required
                                    placeholder="John Doe"
                                />

                                <flux:input
                                    wire:model="email"
                                    :label="__('Email Address')"
                                    type="email"
                                    required
                                    placeholder="john@example.com"
                                />
                            </div>

                            <flux:input
                                wire:model="subject"
                                :label="__('Subject')"
                                type="text"
                                placeholder="Project inquiry or collaboration"
                            />

                            <flux:textarea
                                wire:model="message"
                                :label="__('Message')"
                                rows="5"
                                required
                                placeholder="Tell me about your project, timeline, or question..."
                            />

                            <div class="flex items-center justify-between pt-2">
                                <span class="text-xs text-zinc-500">
                                    {{ __('Direct & confidential inquiry') }}
                                </span>

                                <flux:button
                                    variant="primary"
                                    type="submit"
                                    wire:loading.attr="disabled"
                                    wire:target="submitContact"
                                >
                                    {{ __('Send Message') }}
                                </flux:button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="border-t border-white/10 py-8">
        <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-4 px-4 text-xs text-zinc-500 sm:flex-row sm:px-6 lg:px-8">
            <span>© {{ date('Y') }} {{ $profile->name }}. {{ __('All rights reserved.') }}</span>
            <div class="flex items-center gap-5">
                <a href="#home" class="hover:text-zinc-300">{{ __('Home') }}</a>
                <a href="#projects" class="hover:text-zinc-300">{{ __('Projects') }}</a>
                <a href="#experience" class="hover:text-zinc-300">{{ __('Experience') }}</a>
                <a href="#skills" class="hover:text-zinc-300">{{ __('Skills') }}</a>
            </div>
        </div>
    </footer>

    {{-- All Projects Modal --}}
    <flux:modal wire:model="showAllProjectsModal" class="max-w-5xl">
        <div class="space-y-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <flux:heading size="xl">{{ __('All Projects') }} ({{ $totalProjects }})</flux:heading>
                    <flux:text class="mt-1">
                        {{ __('Complete archive of published portfolio projects.') }}
                    </flux:text>
                </div>
            </div>

            <div class="grid max-h-[70vh] gap-5 overflow-y-auto pr-1 md:grid-cols-2">
                @foreach ($this->projects as $project)
                    <div
                        wire:key="modal-project-{{ $project->id }}"
                        class="flex flex-col overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900"
                    >
                        @if ($project->thumbnailUrl())
                            <img
                                src="{{ $project->thumbnailUrl() }}"
                                alt="{{ $project->title }}"
                                class="h-40 w-full object-cover"
                            />
                        @endif

                        <div class="flex flex-1 flex-col p-5">
                            <div class="flex items-start justify-between gap-2">
                                <flux:heading size="lg">{{ $project->title }}</flux:heading>
                                @if ($project->is_featured)
                                    <flux:badge size="sm" color="amber">{{ __('Featured') }}</flux:badge>
                                @endif
                            </div>

                            <flux:text class="mt-2 flex-1 text-sm">
                                {{ $project->description }}
                            </flux:text>

                            <div class="mt-4 flex flex-wrap gap-1.5">
                                @foreach ($project->tech_stack as $tech)
                                    <flux:badge size="sm" color="zinc">{{ $tech }}</flux:badge>
                                @endforeach
                            </div>

                            @if ($project->demo_url || $project->repo_url)
                                <div class="mt-4 flex items-center gap-4 border-t border-zinc-200 pt-3 text-xs font-semibold dark:border-zinc-700">
                                    @if ($project->demo_url)
                                        <a href="{{ $project->demo_url }}" target="_blank" class="text-emerald-600 hover:underline dark:text-emerald-400">
                                            {{ __('Demo →') }}
                                        </a>
                                    @endif

                                    @if ($project->repo_url)
                                        <a href="{{ $project->repo_url }}" target="_blank" class="text-zinc-600 hover:underline dark:text-zinc-400">
                                            {{ __('Repository →') }}
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-end">
                <flux:button type="button" variant="ghost" wire:click="$set('showAllProjectsModal', false)">
                    {{ __('Close') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
