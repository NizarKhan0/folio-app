@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="NIZAR KHAN" {{ $attributes }}>
        <x-slot
            name="logo"
            class="flex aspect-square size-8 items-center justify-center bg-cyan-400 text-zinc-950 shadow-[0_0_14px_rgba(34,211,238,0.45)]"
            style="clip-path: polygon(0 0, calc(100% - 6px) 0, 100% 6px, 100% 100%, 6px 100%, 0 calc(100% - 6px));"
        >
            <x-app-logo-icon class="size-5 text-zinc-950" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="NIZAR KHAN" {{ $attributes }}>
        <x-slot
            name="logo"
            class="flex aspect-square size-8 items-center justify-center bg-cyan-400 text-zinc-950 shadow-[0_0_14px_rgba(34,211,238,0.45)]"
            style="clip-path: polygon(0 0, calc(100% - 6px) 0, 100% 6px, 100% 100%, 6px 100%, 0 calc(100% - 6px));"
        >
            <x-app-logo-icon class="size-5 text-zinc-950" />
        </x-slot>
    </flux:brand>
@endif
