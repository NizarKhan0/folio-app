<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <meta name="robots" content="noindex, nofollow" />
    </head>
    <body class="dark relative h-dvh overflow-hidden bg-[#06070B] text-zinc-100 antialiased selection:bg-cyan-400 selection:text-zinc-950">
        {{-- Cyberpunk Grid, Scanlines & Ambient Neon Glows --}}
        <div class="pointer-events-none fixed inset-0 bg-[linear-gradient(to_right,rgba(34,211,238,0.04)_1px,transparent_1px),linear-gradient(to_bottom,rgba(217,70,239,0.04)_1px,transparent_1px)] bg-[size:44px_44px]"></div>
        <div class="pointer-events-none fixed inset-0 opacity-25 bg-[repeating-linear-gradient(to_bottom,transparent_0px,transparent_2px,rgba(0,0,0,0.35)_3px)]"></div>
        <div class="pointer-events-none fixed -top-40 left-1/4 h-[380px] w-[380px] rounded-full bg-cyan-500/15 blur-[150px]"></div>
        <div class="pointer-events-none fixed right-0 bottom-0 h-[380px] w-[380px] rounded-full bg-fuchsia-600/15 blur-[150px]"></div>

        <div
            class="dark relative flex h-dvh flex-col items-center justify-center overflow-hidden px-4 py-4"
            style="--color-accent: #22d3ee; --color-accent-content: #22d3ee; --color-accent-foreground: #09090b;"
        >
            <div class="flex w-full max-w-md flex-col gap-3.5">
                <a href="{{ route('home') }}" class="group mx-auto flex items-center gap-3" wire:navigate>
                    <span
                        class="flex size-9 shrink-0 items-center justify-center bg-cyan-400 text-zinc-950 shadow-[0_0_20px_rgba(34,211,238,0.45)] transition group-hover:bg-fuchsia-400"
                        style="clip-path: polygon(0 0, calc(100% - 8px) 0, 100% 8px, 100% 100%, 8px 100%, 0 calc(100% - 8px));"
                    >
                        <x-app-logo-icon class="size-5 text-zinc-950" />
                    </span>
                    <div>
                        <div class="text-sm font-black tracking-wider text-white uppercase">NIZAR KHAN</div>
                        <div class="flex items-center gap-1.5 font-mono text-[10px] tracking-widest text-cyan-400">
                            <span class="size-1.5 animate-pulse rounded-full bg-cyan-400"></span>
                            ADMIN // ACCESS
                        </div>
                    </div>
                </a>

                <div class="relative overflow-hidden border border-cyan-400/40 bg-[#0A0D16]/95 shadow-[0_0_50px_rgba(34,211,238,0.14)] backdrop-blur-xl">
                    <div class="h-1 w-full bg-gradient-to-r from-cyan-400 via-fuchsia-500 to-yellow-300"></div>
                    <div class="flex flex-col gap-5 p-5 sm:p-6">
                        {{ $slot }}
                    </div>
                </div>

                <div class="text-center font-mono text-xs">
                    <a href="{{ route('home') }}" class="text-zinc-400 transition hover:text-cyan-300" wire:navigate>
                        ← {{ __('Back to Portfolio') }}
                    </a>
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
