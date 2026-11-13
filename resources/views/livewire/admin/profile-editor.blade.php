<section class="mx-auto w-full max-w-5xl space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">
                {{ __('Identity & Hero') }}
            </div>
            <flux:heading size="xl" class="mt-0.5">{{ __('Profile & Hero') }}</flux:heading>
            <flux:text class="mt-1">
                {{ __('Manage your public portfolio hero identity, bio, availability status, media, and social links.') }}
            </flux:text>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <flux:badge :color="$is_available ? 'green' : 'zinc'" size="lg">
                {{ $is_available ? __('Available for Work') : __('Currently Unavailable') }}
            </flux:badge>

            <flux:button
                type="button"
                variant="outline"
                wire:click="toggleAvailability"
                wire:loading.attr="disabled"
                wire:target="toggleAvailability"
            >
                {{ $is_available ? __('Set Unavailable') : __('Set Available') }}
            </flux:button>
        </div>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-xs dark:border-zinc-700/80 dark:bg-zinc-900">
            <flux:heading size="lg">{{ __('Media & Hero Appearance') }}</flux:heading>
            <flux:text class="mt-1 mb-6">
                {{ __('Upload a profile avatar and an optional hero banner image. If no banner is uploaded, a clean CSS grid and gradient background will be used.') }}
            </flux:text>

            <div class="grid gap-8 md:grid-cols-2">
                <div class="space-y-4">
                    <flux:label>{{ __('Profile Avatar (Max 2MB)') }}</flux:label>

                    <div class="flex items-center gap-4 rounded-xl border border-zinc-200/80 bg-zinc-50/60 p-4 dark:border-zinc-700/80 dark:bg-zinc-800/40">
                        @if ($avatar && $avatar->isPreviewable())
                            <img
                                src="{{ $avatar->temporaryUrl() }}"
                                alt="{{ __('Avatar preview') }}"
                                class="size-20 shrink-0 rounded-2xl border border-zinc-200 object-cover dark:border-zinc-700"
                            />
                        @elseif ($existingAvatarPath)
                            <img
                                src="{{ Storage::disk('public')->url($existingAvatarPath) }}"
                                alt="{{ $name }}"
                                class="size-20 shrink-0 rounded-2xl border border-zinc-200 object-cover dark:border-zinc-700"
                            />
                        @else
                            <div class="flex size-20 shrink-0 items-center justify-center rounded-2xl border border-dashed border-zinc-300 bg-zinc-100 text-lg font-semibold text-zinc-600 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                {{ \Illuminate\Support\Str::initials($name ?: 'NK', true) }}
                            </div>
                        @endif

                        <div class="flex min-w-0 flex-1 flex-col gap-2">
                            <input
                                type="file"
                                wire:model="avatar"
                                accept="image/*"
                                class="block w-full text-sm text-zinc-600 file:me-3 file:rounded-lg file:border-0 file:bg-zinc-200/80 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-zinc-800 hover:file:bg-zinc-300 dark:text-zinc-400 dark:file:bg-zinc-700 dark:file:text-zinc-200 dark:hover:file:bg-zinc-600"
                            />

                            @if ($existingAvatarPath)
                                <div>
                                    <flux:button
                                        type="button"
                                        size="xs"
                                        variant="ghost"
                                        icon="trash"
                                        wire:click="removeAvatar"
                                        wire:loading.attr="disabled"
                                        wire:target="removeAvatar"
                                    >
                                        {{ __('Remove Avatar') }}
                                    </flux:button>
                                </div>
                            @endif
                        </div>
                    </div>

                    @error('avatar')
                        <flux:text class="text-sm text-red-600 dark:text-red-400">{{ $message }}</flux:text>
                    @enderror
                </div>

                <div class="space-y-4">
                    <flux:label>{{ __('Hero Banner (Optional, Max 4MB)') }}</flux:label>

                    <div class="space-y-3 rounded-xl border border-zinc-200/80 bg-zinc-50/60 p-4 dark:border-zinc-700/80 dark:bg-zinc-800/40">
                        @if ($banner && $banner->isPreviewable())
                            <img
                                src="{{ $banner->temporaryUrl() }}"
                                alt="{{ __('Banner preview') }}"
                                class="h-24 w-full rounded-lg border border-zinc-200 object-cover dark:border-zinc-700"
                            />
                        @elseif ($existingBannerPath)
                            <img
                                src="{{ Storage::disk('public')->url($existingBannerPath) }}"
                                alt="{{ __('Current hero banner') }}"
                                class="h-24 w-full rounded-lg border border-zinc-200 object-cover dark:border-zinc-700"
                            />
                        @else
                            <div class="flex h-24 w-full items-center justify-center rounded-lg border border-dashed border-zinc-300 bg-gradient-to-br from-zinc-100 via-zinc-50 to-zinc-200 text-xs font-medium text-zinc-600 dark:border-zinc-700 dark:from-zinc-900 dark:via-zinc-800 dark:to-zinc-950 dark:text-zinc-400">
                                {{ __('Default: CSS Grid & Gradient Background') }}
                            </div>
                        @endif

                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <input
                                type="file"
                                wire:model="banner"
                                accept="image/*"
                                class="block text-sm text-zinc-600 file:me-3 file:rounded-lg file:border-0 file:bg-zinc-200/80 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-zinc-800 hover:file:bg-zinc-300 dark:text-zinc-400 dark:file:bg-zinc-700 dark:file:text-zinc-200 dark:hover:file:bg-zinc-600"
                            />

                            @if ($existingBannerPath)
                                <flux:button
                                    type="button"
                                    size="xs"
                                    variant="ghost"
                                    icon="trash"
                                    wire:click="removeBanner"
                                    wire:loading.attr="disabled"
                                    wire:target="removeBanner"
                                >
                                    {{ __('Remove Banner') }}
                                </flux:button>
                            @endif
                        </div>
                    </div>

                    @error('banner')
                        <flux:text class="text-sm text-red-600 dark:text-red-400">{{ $message }}</flux:text>
                    @enderror
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-xs dark:border-zinc-700/80 dark:bg-zinc-900">
            <flux:heading size="lg">{{ __('Hero Identity') }}</flux:heading>
            <flux:text class="mt-1 mb-6">
                {{ __('Primary details displayed at the top of your public portfolio.') }}
            </flux:text>

            <div class="grid gap-6 md:grid-cols-2">
                <flux:input
                    wire:model="name"
                    :label="__('Display Name')"
                    type="text"
                    required
                    placeholder="Nizar Khan"
                />

                <flux:input
                    wire:model="location"
                    :label="__('Location')"
                    type="text"
                    required
                    placeholder="Putrajaya, Malaysia"
                />

                <div class="md:col-span-2">
                    <flux:input
                        wire:model="headline"
                        :label="__('Hero Headline')"
                        type="text"
                        required
                        placeholder="Hi, I'm Nizar Khan"
                    />
                </div>

                <div class="md:col-span-2">
                    <flux:input
                        wire:model="tagline"
                        :label="__('Tagline')"
                        type="text"
                        required
                        placeholder="Fullstack PHP Developer — Laravel, Livewire, Filament, DevOps"
                    />
                </div>

                <div class="md:col-span-2">
                    <flux:textarea
                        wire:model="bio"
                        :label="__('Biography')"
                        rows="4"
                        placeholder="Write a concise summary of your experience and specialties..."
                    />
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-xs dark:border-zinc-700/80 dark:bg-zinc-900">
            <flux:heading size="lg">{{ __('Contact & Social Links') }}</flux:heading>
            <flux:text class="mt-1 mb-6">
                {{ __('Public links shown in your hero section and contact area.') }}
            </flux:text>

            <div class="grid gap-6 md:grid-cols-2">
                <flux:input
                    wire:model="email"
                    :label="__('Public Contact Email')"
                    type="text"
                    placeholder="test[at]gmail.com"
                />

                <flux:input
                    wire:model="resume_url"
                    :label="__('Resume / CV URL')"
                    type="url"
                    placeholder="https://example.com/resume.pdf"
                />

                <flux:input
                    wire:model="github_url"
                    :label="__('GitHub URL')"
                    type="url"
                    placeholder="https://github.com/username"
                />

                <flux:input
                    wire:model="linkedin_url"
                    :label="__('LinkedIn URL')"
                    type="url"
                    placeholder="https://linkedin.com/in/username"
                />

                <div class="space-y-3 md:col-span-2">
                    <flux:label>{{ __('Upload Resume PDF (Optional, Max 5MB)') }}</flux:label>

                    <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-zinc-200/80 bg-zinc-50/60 p-4 dark:border-zinc-700/80 dark:bg-zinc-800/40">
                        <input
                            type="file"
                            wire:model="resumeFile"
                            accept="application/pdf,.pdf"
                            class="block text-sm text-zinc-600 file:me-3 file:rounded-lg file:border-0 file:bg-zinc-200/80 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-zinc-800 hover:file:bg-zinc-300 dark:text-zinc-400 dark:file:bg-zinc-700 dark:file:text-zinc-200 dark:hover:file:bg-zinc-600"
                        />

                        @if ($existingResumePath)
                            <div class="flex items-center gap-3">
                                <a
                                    href="{{ Storage::disk('public')->url($existingResumePath) }}"
                                    target="_blank"
                                    class="text-sm font-medium text-zinc-900 underline underline-offset-4 hover:text-zinc-700 dark:text-zinc-100 dark:hover:text-zinc-300"
                                >
                                    {{ __('View Current PDF') }}
                                </a>

                                <flux:button
                                    type="button"
                                    size="xs"
                                    variant="ghost"
                                    icon="trash"
                                    wire:click="removeResumeFile"
                                    wire:loading.attr="disabled"
                                    wire:target="removeResumeFile"
                                >
                                    {{ __('Remove PDF') }}
                                </flux:button>
                            </div>
                        @endif
                    </div>

                    <flux:text class="text-xs">
                        {{ __('If a PDF is uploaded, your public portfolio will use it instead of the external Resume / CV URL.') }}
                    </flux:text>

                    @error('resumeFile')
                        <flux:text class="text-sm text-red-600 dark:text-red-400">{{ $message }}</flux:text>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <flux:button
                variant="primary"
                type="submit"
                wire:loading.attr="disabled"
                wire:target="save, avatar, banner, resumeFile"
            >
                {{ __('Save Changes') }}
            </flux:button>
        </div>
    </form>
</section>
