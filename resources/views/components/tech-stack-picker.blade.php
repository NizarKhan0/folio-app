@props([
    'label' => __('Tech Stack'),
    'options' => [],
    'selected' => [],
    'required' => false,
    'errorKey' => 'selectedTechStack',
])

<div class="space-y-2">
    <div class="flex items-center justify-between gap-2">
        <flux:label>
            {{ $label }}@if ($required) <span class="text-red-500">*</span>@endif
        </flux:label>
        <span class="text-xs text-zinc-500 dark:text-zinc-400">
            {{ count($selected) }} {{ __('selected') }}
        </span>
    </div>

    <div class="max-h-48 overflow-y-auto rounded-lg border border-zinc-200 bg-zinc-50 p-3 dark:border-zinc-700 dark:bg-zinc-800/50">
        @if (empty($options))
            <flux:text class="text-xs">
                {{ __('No skills available yet. Add skills in Experience & Skills first.') }}
            </flux:text>
        @else
            <div class="flex flex-wrap gap-2">
                @foreach ($options as $option)
                    <label class="cursor-pointer">
                        <input
                            type="checkbox"
                            value="{{ $option }}"
                            {{ $attributes->wire('model') }}
                            class="peer sr-only"
                        />
                        <span class="inline-flex items-center rounded-md border border-zinc-300 bg-white px-2.5 py-1 text-xs font-medium text-zinc-700 transition peer-checked:border-zinc-900 peer-checked:bg-zinc-900 peer-checked:text-white hover:bg-zinc-100 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-300 dark:peer-checked:border-zinc-100 dark:peer-checked:bg-zinc-100 dark:peer-checked:text-zinc-900 dark:hover:bg-zinc-800">
                            {{ $option }}
                        </span>
                    </label>
                @endforeach
            </div>
        @endif
    </div>

    @error($errorKey)
        <flux:text class="text-sm text-red-600 dark:text-red-400">{{ $message }}</flux:text>
    @enderror
</div>

