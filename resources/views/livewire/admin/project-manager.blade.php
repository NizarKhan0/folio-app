<section class="mx-auto w-full max-w-6xl space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="font-mono text-xs font-bold uppercase tracking-widest text-cyan-700 dark:text-cyan-400">
                02 // {{ __('Showcase') }}
            </div>
            <flux:heading size="xl" class="mt-0.5">{{ __('Projects') }}</flux:heading>
            <flux:text class="mt-1">
                {{ __('Manage your portfolio projects, tech stack badges, links, and display order.') }}
            </flux:text>
        </div>

        <flux:button variant="primary" icon="plus" wire:click="openCreateModal">
            {{ __('Add Project') }}
        </flux:button>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-xs dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <flux:text class="text-xs font-semibold uppercase tracking-wider">{{ __('Total Projects') }}</flux:text>
                <div class="flex size-9 items-center justify-center rounded-xl bg-zinc-500/10 text-zinc-600 dark:text-zinc-300">
                    <flux:icon name="folder-git-2" class="size-4" />
                </div>
            </div>
            <div class="mt-3 text-3xl font-bold tracking-tight text-zinc-900 tabular-nums dark:text-zinc-100">
                {{ $this->totalProjects }}
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-xs dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <flux:text class="text-xs font-semibold uppercase tracking-wider">{{ __('Published') }}</flux:text>
                <div class="flex size-9 items-center justify-center rounded-xl bg-cyan-500/10 text-cyan-700 dark:text-cyan-400">
                    <flux:icon name="check-circle" class="size-4" />
                </div>
            </div>
            <div class="mt-3 text-3xl font-bold tracking-tight text-zinc-900 tabular-nums dark:text-zinc-100">
                {{ $this->publishedCount }}
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-xs dark:border-zinc-700/80 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <flux:text class="text-xs font-semibold uppercase tracking-wider">{{ __('Featured') }}</flux:text>
                <div class="flex size-9 items-center justify-center rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400">
                    <flux:icon name="star" class="size-4" />
                </div>
            </div>
            <div class="mt-3 text-3xl font-bold tracking-tight text-zinc-900 tabular-nums dark:text-zinc-100">
                {{ $this->featuredCount }} <span class="text-base font-medium text-zinc-400">/ {{ \App\Livewire\Admin\ProjectManager::MAX_FEATURED_PROJECTS }}</span>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-xs dark:border-zinc-700/80 dark:bg-zinc-900">
        @if ($this->projects->isEmpty())
            <div class="p-14 text-center">
                <flux:heading size="lg">{{ __('No projects yet') }}</flux:heading>
                <flux:text class="mt-1">
                    {{ __('Click "Add Project" to showcase your first portfolio project.') }}
                </flux:text>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 text-left text-sm dark:divide-zinc-700/80">
                    <thead class="bg-zinc-50/80 text-[11px] font-semibold uppercase tracking-wider text-zinc-500 dark:bg-zinc-800/50 dark:text-zinc-400">
                        <tr>
                            <th class="px-5 py-3.5">{{ __('Order') }}</th>
                            <th class="px-5 py-3.5">{{ __('Project') }}</th>
                            <th class="px-5 py-3.5">{{ __('Tech Stack') }}</th>
                            <th class="px-5 py-3.5">{{ __('Links') }}</th>
                            <th class="px-5 py-3.5">{{ __('Status') }}</th>
                            <th class="px-5 py-3.5 text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @foreach ($this->projects as $project)
                            <tr wire:key="project-{{ $project->id }}" class="align-top transition hover:bg-zinc-50/80 dark:hover:bg-zinc-800/30">
                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="inline-flex items-center gap-1 rounded-lg border border-zinc-200 bg-zinc-50 px-1.5 py-0.5 dark:border-zinc-700 dark:bg-zinc-800/60">
                                        <span class="px-1 font-mono text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                                            #{{ $project->sort_order }}
                                        </span>

                                        <flux:button
                                            type="button"
                                            size="xs"
                                            variant="ghost"
                                            icon="arrow-up"
                                            wire:click="moveUp({{ $project->id }})"
                                            :disabled="$loop->first"
                                            title="{{ __('Move Up') }}"
                                            aria-label="{{ __('Move Up') }}"
                                        />

                                        <flux:button
                                            type="button"
                                            size="xs"
                                            variant="ghost"
                                            icon="arrow-down"
                                            wire:click="moveDown({{ $project->id }})"
                                            :disabled="$loop->last"
                                            title="{{ __('Move Down') }}"
                                            aria-label="{{ __('Move Down') }}"
                                        />
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-start gap-3.5">
                                        @if ($project->thumbnailUrl())
                                            <img
                                                src="{{ $project->thumbnailUrl() }}"
                                                alt="{{ $project->title }}"
                                                class="h-12 w-16 shrink-0 rounded-lg border border-zinc-200 object-cover dark:border-zinc-700"
                                            />
                                        @else
                                            <div class="flex h-12 w-16 shrink-0 items-center justify-center rounded-lg border border-dashed border-zinc-300 bg-zinc-50 text-[10px] font-medium text-zinc-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-400">
                                                {{ __('No Image') }}
                                            </div>
                                        @endif

                                        <div class="min-w-0">
                                            <div class="font-semibold text-zinc-900 dark:text-zinc-100">
                                                {{ $project->title }}
                                            </div>
                                            <div class="font-mono text-xs text-zinc-500 dark:text-zinc-400">
                                                /{{ $project->slug }}
                                            </div>
                                            <p class="mt-1 line-clamp-2 max-w-md text-xs text-zinc-600 dark:text-zinc-400">
                                                {{ $project->description }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex max-w-xs flex-wrap gap-1.5">
                                        @foreach ($project->tech_stack as $tech)
                                            <flux:badge size="sm" color="zinc">{{ $tech }}</flux:badge>
                                        @endforeach
                                    </div>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="flex flex-col gap-1 text-xs">
                                        @if ($project->demo_url)
                                            <a
                                                href="{{ $project->demo_url }}"
                                                target="_blank"
                                                class="font-medium text-cyan-700 underline underline-offset-2 hover:text-cyan-600 dark:text-cyan-400 dark:hover:text-cyan-300"
                                            >
                                                {{ __('Demo →') }}
                                            </a>
                                        @endif

                                        @if ($project->repo_url)
                                            <a
                                                href="{{ $project->repo_url }}"
                                                target="_blank"
                                                class="font-medium text-zinc-600 underline underline-offset-2 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-200"
                                            >
                                                {{ __('Repository →') }}
                                            </a>
                                        @endif

                                        @if (! $project->demo_url && ! $project->repo_url)
                                            <span class="text-zinc-400">—</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="flex flex-col items-start gap-1.5">
                                        <button
                                            type="button"
                                            wire:click="togglePublished({{ $project->id }})"
                                            class="cursor-pointer"
                                        >
                                            <flux:badge :color="$project->is_published ? 'cyan' : 'zinc'" size="sm">
                                                {{ $project->is_published ? __('Published') : __('Draft') }}
                                            </flux:badge>
                                        </button>

                                        <button
                                            type="button"
                                            wire:click="toggleFeatured({{ $project->id }})"
                                            class="cursor-pointer"
                                            title="{{ __('Toggle Featured (max :max)', ['max' => \App\Livewire\Admin\ProjectManager::MAX_FEATURED_PROJECTS]) }}"
                                        >
                                            <flux:badge :color="$project->is_featured ? 'amber' : 'zinc'" size="sm">
                                                {{ $project->is_featured ? __('Featured') : __('Standard') }}
                                            </flux:badge>
                                        </button>
                                    </div>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-right">
                                    <div class="inline-flex items-center gap-1 rounded-lg border border-zinc-200 bg-zinc-50/70 p-1 dark:border-zinc-700 dark:bg-zinc-800/50">
                                        <flux:button
                                            type="button"
                                            size="xs"
                                            variant="ghost"
                                            icon="pencil-square"
                                            wire:click="openEditModal({{ $project->id }})"
                                            title="{{ __('Edit') }}"
                                            aria-label="{{ __('Edit') }}"
                                        />

                                        <flux:button
                                            type="button"
                                            size="xs"
                                            variant="danger"
                                            icon="trash"
                                            wire:click="confirmDelete({{ $project->id }})"
                                            title="{{ __('Delete') }}"
                                            aria-label="{{ __('Delete') }}"
                                        />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <flux:modal wire:model="showFormModal" class="max-w-2xl">
        <form wire:submit="save" class="space-y-5">
            <div>
                <flux:heading size="lg">
                    {{ $editingId ? __('Edit Project') : __('Add Project') }}
                </flux:heading>
                <flux:text class="mt-1">
                    {{ __('Provide project details, technologies used, and public links.') }}
                </flux:text>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <flux:input
                    wire:model.live.debounce.250ms="title"
                    :label="__('Project Title')"
                    type="text"
                    required
                    placeholder="Personal portfolio website"
                />

                <flux:input
                    wire:model="slug"
                    :label="__('Slug')"
                    type="text"
                    required
                    placeholder="personal-portfolio-website"
                />
            </div>

            <flux:textarea
                wire:model="description"
                :label="__('Description')"
                rows="4"
                required
                placeholder="Describe the project architecture, key features, and impact..."
            />

            <x-tech-stack-picker
                :label="__('Tech Stack')"
                :options="$this->availableSkills"
                :selected="$selectedTechStack"
                :required="true"
                wire:model.live="selectedTechStack"
            />

            <div class="grid gap-4 sm:grid-cols-2">
                <flux:input
                    wire:model="demo_url"
                    :label="__('Demo URL')"
                    type="text"
                    placeholder="https://me.zaryue.com/ or #"
                />

                <flux:input
                    wire:model="repo_url"
                    :label="__('Repository URL')"
                    type="text"
                    placeholder="https://github.com/username/repo or #"
                />
            </div>

            <div class="space-y-3 rounded-xl border border-zinc-200 bg-zinc-50/70 p-4 dark:border-zinc-700/80 dark:bg-zinc-800/40">
                <div class="flex items-center justify-between gap-2">
                    <flux:label>{{ __('Project Thumbnail') }}</flux:label>
                    <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ __('Optional · Max 3MB') }}</span>
                </div>

                @if ($thumbnail && $thumbnail->isPreviewable())
                    <img
                        src="{{ $thumbnail->temporaryUrl() }}"
                        alt="{{ __('Thumbnail preview') }}"
                        class="h-32 w-56 rounded-lg border border-zinc-200 object-cover dark:border-zinc-700"
                    />
                @elseif ($existingThumbnailPath)
                    <div class="flex flex-wrap items-center gap-4">
                        <img
                            src="{{ Storage::disk('public')->url($existingThumbnailPath) }}"
                            alt="{{ $title }}"
                            class="h-32 w-56 rounded-lg border border-zinc-200 object-cover dark:border-zinc-700"
                        />

                        <flux:button
                            type="button"
                            size="xs"
                            variant="ghost"
                            icon="trash"
                            wire:click="removeThumbnail"
                        >
                            {{ __('Remove Thumbnail') }}
                        </flux:button>
                    </div>
                @endif

                <input
                    type="file"
                    wire:model="thumbnail"
                    accept="image/*"
                    class="block w-full cursor-pointer rounded-lg border border-dashed border-zinc-300 bg-white p-2 text-sm text-zinc-600 file:me-3 file:cursor-pointer file:rounded-md file:border-0 file:bg-zinc-900 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:file:bg-zinc-700 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-400 dark:file:bg-zinc-100 dark:file:text-zinc-900 dark:hover:file:bg-zinc-200"
                />

                @error('thumbnail')
                    <flux:text class="text-sm text-red-600 dark:text-red-400">{{ $message }}</flux:text>
                @enderror
            </div>

            <div class="rounded-xl border border-zinc-200 bg-zinc-50/70 p-4 dark:border-zinc-700/80 dark:bg-zinc-800/40">
                <div class="grid items-center gap-4 sm:grid-cols-12">
                    <div class="sm:col-span-4">
                        <flux:input
                            wire:model="sort_order"
                            :label="__('Sort Order')"
                            type="number"
                            min="0"
                            required
                        />
                    </div>

                    <div class="flex flex-col gap-3 sm:col-span-8 sm:flex-row sm:items-center sm:justify-end sm:gap-6 sm:pt-5">
                        <flux:checkbox
                            wire:model="is_featured"
                            :label="__('Featured (:count/:max)', ['count' => $this->featuredCount, 'max' => \App\Livewire\Admin\ProjectManager::MAX_FEATURED_PROJECTS])"
                            :disabled="$this->featuredCount >= \App\Livewire\Admin\ProjectManager::MAX_FEATURED_PROJECTS && ! $is_featured"
                        />

                        <flux:checkbox wire:model="is_published" :label="__('Published')" />
                    </div>
                </div>

                @if ($this->featuredCount >= \App\Livewire\Admin\ProjectManager::MAX_FEATURED_PROJECTS && ! $is_featured)
                    <p class="mt-2.5 text-xs text-amber-600 dark:text-amber-400">
                        {{ __('Maximum :max featured projects reached. Unfeature another project to feature this one.', ['max' => \App\Livewire\Admin\ProjectManager::MAX_FEATURED_PROJECTS]) }}
                    </p>
                @endif

                @error('is_featured')
                    <p class="mt-2 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <flux:button type="button" variant="ghost" wire:click="$set('showFormModal', false)">
                    {{ __('Cancel') }}
                </flux:button>

                <flux:button
                    type="submit"
                    variant="primary"
                    wire:loading.attr="disabled"
                    wire:target="save, thumbnail"
                >
                    {{ $editingId ? __('Save Changes') : __('Create Project') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal wire:model="showDeleteModal" class="max-w-md">
        <div class="space-y-4">
            <flux:heading size="lg">{{ __('Delete Project') }}</flux:heading>
            <flux:text>
                {{ __('Are you sure you want to delete ":title"? This action cannot be undone.', ['title' => $deletingTitle]) }}
            </flux:text>

            <div class="flex justify-end gap-3 pt-2">
                <flux:button type="button" variant="ghost" wire:click="$set('showDeleteModal', false)">
                    {{ __('Cancel') }}
                </flux:button>

                <flux:button type="button" variant="danger" wire:click="delete">
                    {{ __('Delete Project') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</section>
