@php
    $seoProfile = \App\Models\Profile::current();
    $seoSkills = \App\Models\Skill::query()->orderBy('sort_order')->pluck('name')->all();
    $seoProjects = \App\Models\Project::published()->take(6)->get();
    $latestExperience = \App\Models\Experience::query()->orderBy('sort_order')->first();

    $seoTitle = trim($seoProfile->name.' — Fullstack Laravel & PHP Developer Malaysia', ' —');
    $seoDescription = \Illuminate\Support\Str::limit(
        trim((string) ($seoProfile->bio ?: $seoProfile->tagline)),
        160,
        '...'
    );
    $seoUrl = url('/');
    $seoImage = $seoProfile->bannerUrl() ?: $seoProfile->avatarUrl() ?: asset('favicon.ico');
    $seoKeywords = implode(', ', array_unique(array_filter(array_merge(
        [$seoProfile->name, 'Fullstack PHP Developer', 'Laravel Developer', $seoProfile->location],
        array_slice($seoSkills, 0, 14)
    ))));
    $sameAs = array_values(array_filter([
        $seoProfile->github_url,
        $seoProfile->linkedin_url,
    ]));

    $jsonLd = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'WebSite',
                '@id' => $seoUrl.'#website',
                'url' => $seoUrl,
                'name' => $seoTitle,
                'description' => $seoDescription,
                'inLanguage' => str_replace('_', '-', app()->getLocale()),
            ],
            [
                '@type' => 'ProfilePage',
                '@id' => $seoUrl.'#profilepage',
                'url' => $seoUrl,
                'name' => $seoTitle,
                'description' => $seoDescription,
                'isPartOf' => ['@id' => $seoUrl.'#website'],
                'mainEntity' => ['@id' => $seoUrl.'#person'],
            ],
            array_filter([
                '@type' => 'Person',
                '@id' => $seoUrl.'#person',
                'name' => $seoProfile->name,
                'url' => $seoUrl,
                'image' => $seoProfile->avatarUrl(),
                'jobTitle' => $seoProfile->tagline,
                'description' => $seoProfile->bio,
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => $seoProfile->location,
                ],
                'worksFor' => $latestExperience ? [
                    '@type' => 'Organization',
                    'name' => $latestExperience->company,
                ] : null,
                'knowsAbout' => $seoSkills,
                'sameAs' => $sameAs !== [] ? $sameAs : null,
            ], static fn (mixed $v): bool => $v !== null && $v !== []),
            [
                '@type' => 'ItemList',
                '@id' => $seoUrl.'#projects',
                'name' => 'Featured Projects',
                'itemListElement' => $seoProjects->values()->map(static fn (\App\Models\Project $project, int $index): array => [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'item' => array_filter([
                        '@type' => 'CreativeWork',
                        'name' => $project->title,
                        'description' => $project->description,
                        'url' => $project->demo_url && str_starts_with($project->demo_url, 'http') ? $project->demo_url : $seoUrl.'#projects',
                        'image' => $project->thumbnailUrl(),
                        'keywords' => implode(', ', $project->tech_stack),
                    ], static fn (mixed $v): bool => $v !== null && $v !== ''),
                ])->all(),
            ],
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $seoTitle }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" />
        <link rel="icon" sizes="any" href="{{ asset('favicon.ico') }}" />
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}" />

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            if (! localStorage.getItem('flux.appearance')) {
                localStorage.setItem('flux.appearance', 'dark');
            }
        </script>
        @fluxAppearance

        <meta name="description" content="{{ $seoDescription }}" />
        <meta name="keywords" content="{{ $seoKeywords }}" />
        <meta name="author" content="{{ $seoProfile->name }}" />
        <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" />
        <meta name="theme-color" content="#06070B" />
        <link rel="canonical" href="{{ $seoUrl }}" />

        {{-- Open Graph / Facebook / LinkedIn --}}
        <meta property="og:type" content="profile" />
        <meta property="og:locale" content="en_US" />
        <meta property="og:site_name" content="{{ $seoProfile->name }}" />
        <meta property="og:title" content="{{ $seoTitle }}" />
        <meta property="og:description" content="{{ $seoDescription }}" />
        <meta property="og:url" content="{{ $seoUrl }}" />
        <meta property="og:image" content="{{ $seoImage }}" />

        {{-- Twitter / X Card --}}
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="{{ $seoTitle }}" />
        <meta name="twitter:description" content="{{ $seoDescription }}" />
        <meta name="twitter:image" content="{{ $seoImage }}" />

        {{-- Google Structured Data (JSON-LD) --}}
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 antialiased dark:bg-[#06070B] dark:text-zinc-100">
        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
