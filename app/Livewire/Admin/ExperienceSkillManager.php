<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Experience;
use App\Models\Skill;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::app')]
#[Title('Experience & Skills')]
class ExperienceSkillManager extends Component
{
    public string $activeTab = 'experiences';

    public bool $showExperienceModal = false;

    public bool $showSkillModal = false;

    public bool $showDeleteModal = false;

    public ?int $editingExperienceId = null;

    public ?int $editingSkillId = null;

    public string $deletingType = '';

    public ?int $deletingId = null;

    public string $deletingLabel = '';

    // Experience fields
    public string $role = '';

    public string $company = '';

    public ?string $location = null;

    public string $period = '';

    public string $description = '';

    public string $techStackInput = '';

    public int $experienceSortOrder = 0;

    // Skill fields
    public string $skillName = '';

    public string $skillCategory = 'backend';

    public int $skillSortOrder = 0;

    /**
     * @return Collection<int, Experience>
     */
    #[Computed]
    public function experiences(): Collection
    {
        return Experience::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    /**
     * @return Collection<int, Skill>
     */
    #[Computed]
    public function skills(): Collection
    {
        return Skill::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function openCreateExperienceModal(): void
    {
        $this->resetExperienceForm();
        $this->experienceSortOrder = ((int) Experience::query()->max('sort_order')) + 1;
        $this->showExperienceModal = true;
    }

    public function openEditExperienceModal(int $id): void
    {
        $experience = Experience::query()->findOrFail($id);

        $this->resetValidation();
        $this->editingExperienceId = $experience->id;
        $this->role = $experience->role;
        $this->company = $experience->company;
        $this->location = $experience->location;
        $this->period = $experience->period;
        $this->description = $experience->description;
        $this->techStackInput = implode(', ', $experience->tech_stack ?? []);
        $this->experienceSortOrder = $experience->sort_order;
        $this->showExperienceModal = true;
    }

    public function saveExperience(): void
    {
        $validated = $this->validate([
            'role' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'period' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'techStackInput' => ['nullable', 'string', 'max:1000'],
            'experienceSortOrder' => ['required', 'integer', 'min:0'],
        ]);

        $techStack = $this->parseTechStack($validated['techStackInput'] ?? '');

        $data = [
            'role' => $validated['role'],
            'company' => $validated['company'],
            'location' => ($validated['location'] ?? '') !== '' ? $validated['location'] : null,
            'period' => $validated['period'],
            'description' => $validated['description'],
            'tech_stack' => $techStack !== [] ? $techStack : null,
            'sort_order' => $validated['experienceSortOrder'],
        ];

        if ($this->editingExperienceId !== null) {
            Experience::query()->findOrFail($this->editingExperienceId)->update($data);
            $message = 'Experience updated.';
        } else {
            Experience::query()->create($data);
            $message = 'Experience added.';
        }

        $this->showExperienceModal = false;
        $this->resetExperienceForm();
        unset($this->experiences);

        Flux::toast(variant: 'success', text: $message);
    }

    public function moveExperienceUp(int $id): void
    {
        $this->swapOrder(Experience::class, $id, -1);
        unset($this->experiences);
    }

    public function moveExperienceDown(int $id): void
    {
        $this->swapOrder(Experience::class, $id, 1);
        unset($this->experiences);
    }

    public function openCreateSkillModal(): void
    {
        $this->resetSkillForm();
        $this->skillSortOrder = ((int) Skill::query()->max('sort_order')) + 1;
        $this->showSkillModal = true;
    }

    public function openEditSkillModal(int $id): void
    {
        $skill = Skill::query()->findOrFail($id);

        $this->resetValidation();
        $this->editingSkillId = $skill->id;
        $this->skillName = $skill->name;
        $this->skillCategory = $skill->category;
        $this->skillSortOrder = $skill->sort_order;
        $this->showSkillModal = true;
    }

    public function saveSkill(): void
    {
        $validated = $this->validate([
            'skillName' => [
                'required',
                'string',
                'max:100',
                Rule::unique('skills', 'name')->ignore($this->editingSkillId),
            ],
            'skillCategory' => ['required', Rule::in(array_keys(Skill::CATEGORIES))],
            'skillSortOrder' => ['required', 'integer', 'min:0'],
        ]);

        $data = [
            'name' => $validated['skillName'],
            'category' => $validated['skillCategory'],
            'sort_order' => $validated['skillSortOrder'],
        ];

        if ($this->editingSkillId !== null) {
            Skill::query()->findOrFail($this->editingSkillId)->update($data);
            $message = 'Skill updated.';
        } else {
            Skill::query()->create($data);
            $message = 'Skill added.';
        }

        $this->showSkillModal = false;
        $this->resetSkillForm();
        unset($this->skills);

        Flux::toast(variant: 'success', text: $message);
    }

    public function moveSkillUp(int $id): void
    {
        $this->swapOrder(Skill::class, $id, -1);
        unset($this->skills);
    }

    public function moveSkillDown(int $id): void
    {
        $this->swapOrder(Skill::class, $id, 1);
        unset($this->skills);
    }

    public function confirmDelete(string $type, int $id): void
    {
        if ($type === 'experience') {
            $experience = Experience::query()->findOrFail($id);
            $this->deletingType = 'experience';
            $this->deletingId = $experience->id;
            $this->deletingLabel = "{$experience->role} at {$experience->company}";
            $this->showDeleteModal = true;

            return;
        }

        if ($type === 'skill') {
            $skill = Skill::query()->findOrFail($id);
            $this->deletingType = 'skill';
            $this->deletingId = $skill->id;
            $this->deletingLabel = $skill->name;
            $this->showDeleteModal = true;
        }
    }

    public function delete(): void
    {
        if ($this->deletingId === null) {
            return;
        }

        if ($this->deletingType === 'experience') {
            Experience::query()->findOrFail($this->deletingId)->delete();
            unset($this->experiences);
            $message = 'Experience deleted.';
        } elseif ($this->deletingType === 'skill') {
            Skill::query()->findOrFail($this->deletingId)->delete();
            unset($this->skills);
            $message = 'Skill deleted.';
        } else {
            return;
        }

        $this->showDeleteModal = false;
        $this->deletingType = '';
        $this->deletingId = null;
        $this->deletingLabel = '';

        Flux::toast(variant: 'success', text: $message);
    }

    public function render(): View
    {
        return view('livewire.admin.experience-skill-manager');
    }

    /**
     * @return array<int, string>
     */
    private function parseTechStack(string $input): array
    {
        $items = array_map(
            static fn (string $item): string => trim($item),
            explode(',', $input),
        );

        return array_values(array_unique(array_filter(
            $items,
            static fn (string $item): bool => $item !== '',
        )));
    }

    /**
     * @param  class-string<Experience|Skill>  $modelClass
     */
    private function swapOrder(string $modelClass, int $id, int $direction): void
    {
        $ordered = $modelClass::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->values();

        foreach ($ordered as $index => $item) {
            $expectedOrder = $index + 1;
            if ($item->sort_order !== $expectedOrder) {
                $item->update(['sort_order' => $expectedOrder]);
            }
        }

        $currentIndex = $ordered->search(static fn (Experience|Skill $item): bool => $item->id === $id);

        if (! is_int($currentIndex)) {
            return;
        }

        $targetIndex = $currentIndex + $direction;

        if ($targetIndex < 0 || $targetIndex >= $ordered->count()) {
            return;
        }

        /** @var Experience|Skill $current */
        $current = $ordered->get($currentIndex);
        /** @var Experience|Skill $adjacent */
        $adjacent = $ordered->get($targetIndex);

        $currentOrder = $current->sort_order;
        $current->update(['sort_order' => $adjacent->sort_order]);
        $adjacent->update(['sort_order' => $currentOrder]);
    }

    private function resetExperienceForm(): void
    {
        $this->resetValidation();
        $this->editingExperienceId = null;
        $this->role = '';
        $this->company = '';
        $this->location = null;
        $this->period = '';
        $this->description = '';
        $this->techStackInput = '';
        $this->experienceSortOrder = 0;
    }

    private function resetSkillForm(): void
    {
        $this->resetValidation();
        $this->editingSkillId = null;
        $this->skillName = '';
        $this->skillCategory = 'backend';
        $this->skillSortOrder = 0;
    }
}

