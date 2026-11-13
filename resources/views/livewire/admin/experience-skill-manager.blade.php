<section class="mx-auto w-full max-w-6xl space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl">{{ __('Experience & Skills') }}</flux:heading>
            <flux:text class="mt-1">
                {{ __('Manage your career timeline, tech stacks, and technical skills.') }}
            </flux:text>
        </div>

        <div class="flex items-center gap-3">
            @if ($activeTab === 'experiences')
                <flux:button variant="primary" wire:click="openCreateExperienceModal">
                    {{ __('Add Experience') }}
                </flux:button>
            @else
                <flux:button variant="primary" wire:click="openCreateSkillModal">
                    {{ __('Add Skill') }}
                </flux:button>
            @endif
        </div>
    </div>

    <div class="flex items-center gap-2 border-b border-zinc-200 pb-3 dark:border-zinc-700">
        <flux:button
            type="button"
            size="sm"
            :variant="$activeTab === 'experiences' ? 'primary' : 'ghost'"
            wire:click="$set('activeTab', 'experiences')"
        >
            {{ __('Experience') }} ({{ $this->experiences->count() }})
        </flux:button>

        <flux:button
            type="button"
            size="sm"
            :variant="$activeTab === 'skills' ? 'primary' : 'ghost'"
            wire:click="$set('activeTab', 'skills')"
        >
            {{ __('Skills') }} ({{ $this->skills->count() }})
        </flux:button>
    </div>

    @if ($activeTab === 'experiences')
        <div class="space-y-4">
            @forelse ($this->experiences as $experience)
                <div
                    wire:key="experience-{{ $experience->id }}"
                    class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900"
                >
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div class="flex items-start gap-4">
                            <div class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-zinc-900 text-lg font-bold text-white dark:bg-zinc-100 dark:text-zinc-900">
                                {{ $experience->companyInitial() }}
                            </div>

                            <div class="space-y-1.5">
                                <div class="flex flex-wrap items-center gap-2">
                                    <flux:heading size="lg">{{ $experience->role }}</flux:heading>
                                    <flux:badge size="sm" color="zinc">#{{ $experience->sort_order }}</flux:badge>
                                </div>

                                <div class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                    {{ $experience->company }}
                                    @if ($experience->location)
                                        <span class="text-zinc-400">•</span> {{ $experience->location }}
                                    @endif
                                </div>

                                <div class="text-xs font-medium text-zinc-500 dark:text-zinc-400">
                                    {{ $experience->period }}
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <flux:button
                                type="button"
                                size="xs"
                                variant="ghost"
                                wire:click="moveExperienceUp({{ $experience->id }})"
                                :disabled="$loop->first"
                                title="{{ __('Move Up') }}"
                            >
                                ↑
                            </flux:button>

                            <flux:button
                                type="button"
                                size="xs"
                                variant="ghost"
                                wire:click="moveExperienceDown({{ $experience->id }})"
                                :disabled="$loop->last"
                                title="{{ __('Move Down') }}"
                            >
                                ↓
                            </flux:button>

                            <flux:button
                                type="button"
                                size="sm"
                                variant="outline"
                                wire:click="openEditExperienceModal({{ $experience->id }})"
                            >
                                {{ __('Edit') }}
                            </flux:button>

                            <flux:button
                                type="button"
                                size="sm"
                                variant="danger"
                                wire:click="confirmDelete('experience', {{ $experience->id }})"
                            >
                                {{ __('Delete') }}
                            </flux:button>
                        </div>
                    </div>

                    <flux:text class="mt-4 text-sm leading-relaxed">
                        {{ $experience->description }}
                    </flux:text>

                    @if (! empty($experience->tech_stack))
                        <div class="mt-4 flex flex-wrap gap-1.5">
                            @foreach ($experience->tech_stack as $tech)
                                <flux:badge size="sm" color="zinc">{{ $tech }}</flux:badge>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div class="rounded-xl border border-zinc-200 bg-white p-12 text-center dark:border-zinc-700 dark:bg-zinc-900">
                    <flux:heading size="lg">{{ __('No experiences yet') }}</flux:heading>
                    <flux:text class="mt-1">
                        {{ __('Click "Add Experience" to add your first work experience.') }}
                    </flux:text>
                </div>
            @endforelse
        </div>
    @else
        @if ($this->skills->isEmpty())
            <div class="rounded-xl border border-zinc-200 bg-white p-12 text-center dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg">{{ __('No skills yet') }}</flux:heading>
                <flux:text class="mt-1">
                    {{ __('Click "Add Skill" to add your technical skills.') }}
                </flux:text>
            </div>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($this->skills as $skill)
                    <div
                        wire:key="skill-{{ $skill->id }}"
                        class="flex items-center justify-between gap-3 rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900"
                    >
                        <div class="min-w-0 space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="truncate font-semibold text-zinc-900 dark:text-zinc-100">
                                    {{ $skill->name }}
                                </span>
                                <flux:badge size="sm" color="zinc">#{{ $skill->sort_order }}</flux:badge>
                            </div>
                            <div>
                                <flux:badge size="sm" color="blue">{{ $skill->categoryLabel() }}</flux:badge>
                            </div>
                        </div>

                        <div class="flex shrink-0 items-center gap-1">
                            <flux:button
                                type="button"
                                size="xs"
                                variant="ghost"
                                wire:click="moveSkillUp({{ $skill->id }})"
                                :disabled="$loop->first"
                                title="{{ __('Move Up') }}"
                            >
                                ↑
                            </flux:button>

                            <flux:button
                                type="button"
                                size="xs"
                                variant="ghost"
                                wire:click="moveSkillDown({{ $skill->id }})"
                                :disabled="$loop->last"
                                title="{{ __('Move Down') }}"
                            >
                                ↓
                            </flux:button>

                            <flux:button
                                type="button"
                                size="xs"
                                variant="outline"
                                wire:click="openEditSkillModal({{ $skill->id }})"
                            >
                                {{ __('Edit') }}
                            </flux:button>

                            <flux:button
                                type="button"
                                size="xs"
                                variant="danger"
                                wire:click="confirmDelete('skill', {{ $skill->id }})"
                            >
                                {{ __('Delete') }}
                            </flux:button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif

    <flux:modal wire:model="showExperienceModal" class="max-w-2xl">
        <form wire:submit="saveExperience" class="space-y-5">
            <div>
                <flux:heading size="lg">
                    {{ $editingExperienceId ? __('Edit Experience') : __('Add Experience') }}
                </flux:heading>
                <flux:text class="mt-1">
                    {{ __('Enter your role, company, period, responsibilities, and tech stack.') }}
                </flux:text>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <flux:input
                    wire:model="role"
                    :label="__('Role / Job Title')"
                    type="text"
                    required
                    placeholder="IT Developer"
                />

                <flux:input
                    wire:model="company"
                    :label="__('Company')"
                    type="text"
                    required
                    placeholder="Opensoft Technologies Sdn Bhd"
                />

                <flux:input
                    wire:model="location"
                    :label="__('Location')"
                    type="text"
                    placeholder="Putrajaya, Malaysia"
                />

                <flux:input
                    wire:model="period"
                    :label="__('Period')"
                    type="text"
                    required
                    placeholder="February 2025 - Present"
                />
            </div>

            <flux:textarea
                wire:model="description"
                :label="__('Description')"
                rows="5"
                required
                placeholder="Summarize key achievements, responsibilities, and measurable impact..."
            />

            <x-tech-stack-picker
                :label="__('Tech Stack')"
                :options="$this->availableSkills"
                :selected="$selectedTechStack"
                wire:model.live="selectedTechStack"
            />

            <flux:input
                wire:model="experienceSortOrder"
                :label="__('Sort Order')"
                type="number"
                min="0"
                required
            />

            <div class="flex justify-end gap-3 pt-2">
                <flux:button type="button" variant="ghost" wire:click="$set('showExperienceModal', false)">
                    {{ __('Cancel') }}
                </flux:button>

                <flux:button type="submit" variant="primary">
                    {{ $editingExperienceId ? __('Save Changes') : __('Add Experience') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal wire:model="showSkillModal" class="max-w-md">
        <form wire:submit="saveSkill" class="space-y-5">
            <div>
                <flux:heading size="lg">
                    {{ $editingSkillId ? __('Edit Skill') : __('Add Skill') }}
                </flux:heading>
                <flux:text class="mt-1">
                    {{ __('Add or update a technical skill and assign its category.') }}
                </flux:text>
            </div>

            <flux:input
                wire:model="skillName"
                :label="__('Skill Name')"
                type="text"
                required
                placeholder="Laravel"
            />

            <flux:select wire:model="skillCategory" :label="__('Category')">
                @foreach (\App\Models\Skill::CATEGORIES as $value => $label)
                    <option value="{{ $value }}">{{ __($label) }}</option>
                @endforeach
            </flux:select>

            <flux:input
                wire:model="skillSortOrder"
                :label="__('Sort Order')"
                type="number"
                min="0"
                required
            />

            <div class="flex justify-end gap-3 pt-2">
                <flux:button type="button" variant="ghost" wire:click="$set('showSkillModal', false)">
                    {{ __('Cancel') }}
                </flux:button>

                <flux:button type="submit" variant="primary">
                    {{ $editingSkillId ? __('Save Changes') : __('Add Skill') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal wire:model="showDeleteModal" class="max-w-md">
        <div class="space-y-4">
            <flux:heading size="lg">
                {{ $deletingType === 'experience' ? __('Delete Experience') : __('Delete Skill') }}
            </flux:heading>
            <flux:text>
                {{ __('Are you sure you want to delete ":name"? This action cannot be undone.', ['name' => $deletingLabel]) }}
            </flux:text>

            <div class="flex justify-end gap-3 pt-2">
                <flux:button type="button" variant="ghost" wire:click="$set('showDeleteModal', false)">
                    {{ __('Cancel') }}
                </flux:button>

                <flux:button type="button" variant="danger" wire:click="delete">
                    {{ __('Delete') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</section>

