<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Project;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Layout('layouts::app')]
#[Title('Projects')]
class ProjectManager extends Component
{
    use WithFileUploads;

    public bool $showFormModal = false;

    public bool $showDeleteModal = false;

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public string $deletingTitle = '';

    public string $title = '';

    public string $slug = '';

    public string $description = '';

    public string $techStackInput = '';

    public ?string $demo_url = null;

    public ?string $repo_url = null;

    public bool $is_featured = true;

    public bool $is_published = true;

    public int $sort_order = 0;

    public ?TemporaryUploadedFile $thumbnail = null;

    public ?string $existingThumbnailPath = null;

    /**
     * Get all projects ordered by sort_order ascending.
     *
     * @return Collection<int, Project>
     */
    #[Computed]
    public function projects(): Collection
    {
        return Project::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    #[Computed]
    public function totalProjects(): int
    {
        return $this->projects()->count();
    }

    #[Computed]
    public function publishedCount(): int
    {
        return $this->projects()->where('is_published', true)->count();
    }

    #[Computed]
    public function featuredCount(): int
    {
        return $this->projects()->where('is_featured', true)->count();
    }

    public function updatedTitle(string $value): void
    {
        if ($this->editingId === null) {
            $this->slug = Str::slug($value);
        }
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $maxOrder = (int) Project::query()->max('sort_order');
        $this->sort_order = $maxOrder + 1;
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $project = Project::query()->findOrFail($id);

        $this->resetValidation();
        $this->editingId = $project->id;
        $this->title = $project->title;
        $this->slug = $project->slug;
        $this->description = $project->description;
        $this->techStackInput = implode(', ', $project->tech_stack);
        $this->demo_url = $project->demo_url;
        $this->repo_url = $project->repo_url;
        $this->is_featured = $project->is_featured;
        $this->is_published = $project->is_published;
        $this->sort_order = $project->sort_order;
        $this->thumbnail = null;
        $this->existingThumbnailPath = $project->thumbnail_path;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $this->slug = Str::slug($this->slug !== '' ? $this->slug : $this->title);

        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('projects', 'slug')->ignore($this->editingId),
            ],
            'description' => ['required', 'string', 'max:5000'],
            'techStackInput' => ['required', 'string', 'max:1000'],
            'demo_url' => ['nullable', 'url', 'max:500'],
            'repo_url' => ['nullable', 'url', 'max:500'],
            'is_featured' => ['required', 'boolean'],
            'is_published' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'thumbnail' => ['nullable', 'image', 'max:3072'],
        ]);

        $techStack = $this->parseTechStack($validated['techStackInput']);

        if ($techStack === []) {
            $this->addError('techStackInput', 'Please enter at least one technology.');

            return;
        }

        $data = [
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'description' => $validated['description'],
            'tech_stack' => $techStack,
            'demo_url' => $validated['demo_url'] !== '' ? $validated['demo_url'] : null,
            'repo_url' => $validated['repo_url'] !== '' ? $validated['repo_url'] : null,
            'is_featured' => $validated['is_featured'],
            'is_published' => $validated['is_published'],
            'sort_order' => $validated['sort_order'],
        ];

        if ($this->thumbnail instanceof TemporaryUploadedFile) {
            $this->deleteStoredFile($this->existingThumbnailPath);
            $storedPath = $this->thumbnail->store('projects/thumbnails', 'public');
            $data['thumbnail_path'] = is_string($storedPath) ? $storedPath : null;
        }

        if ($this->editingId !== null) {
            Project::query()->findOrFail($this->editingId)->update($data);
            $message = 'Project updated.';
        } else {
            Project::query()->create($data);
            $message = 'Project created.';
        }

        $this->showFormModal = false;
        $this->resetForm();
        unset($this->projects, $this->totalProjects, $this->publishedCount, $this->featuredCount);

        Flux::toast(variant: 'success', text: $message);
    }

    public function removeThumbnail(): void
    {
        if ($this->editingId === null) {
            $this->thumbnail = null;

            return;
        }

        $this->deleteStoredFile($this->existingThumbnailPath);
        $this->existingThumbnailPath = null;
        $this->thumbnail = null;

        Project::query()->findOrFail($this->editingId)->update(['thumbnail_path' => null]);
        unset($this->projects);

        Flux::toast(variant: 'success', text: 'Project thumbnail removed.');
    }

    public function togglePublished(int $id): void
    {
        $project = Project::query()->findOrFail($id);
        $project->update(['is_published' => ! $project->is_published]);
        unset($this->projects, $this->publishedCount);

        Flux::toast(
            variant: 'success',
            text: $project->is_published ? 'Project published.' : 'Project moved to draft.',
        );
    }

    public function moveUp(int $id): void
    {
        $this->swapOrder($id, -1);
    }

    public function moveDown(int $id): void
    {
        $this->swapOrder($id, 1);
    }

    public function confirmDelete(int $id): void
    {
        $project = Project::query()->findOrFail($id);

        $this->deletingId = $project->id;
        $this->deletingTitle = $project->title;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingId === null) {
            return;
        }

        $project = Project::query()->findOrFail($this->deletingId);
        $this->deleteStoredFile($project->thumbnail_path);
        $project->delete();

        $this->showDeleteModal = false;
        $this->deletingId = null;
        $this->deletingTitle = '';
        unset($this->projects, $this->totalProjects, $this->publishedCount, $this->featuredCount);

        Flux::toast(variant: 'success', text: 'Project deleted.');
    }

    public function render(): View
    {
        return view('livewire.admin.project-manager');
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

    private function swapOrder(int $id, int $direction): void
    {
        $ordered = Project::query()
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

        $currentIndex = $ordered->search(static fn (Project $item): bool => $item->id === $id);

        if (! is_int($currentIndex)) {
            return;
        }

        $targetIndex = $currentIndex + $direction;

        if ($targetIndex < 0 || $targetIndex >= $ordered->count()) {
            return;
        }

        /** @var Project $current */
        $current = $ordered->get($currentIndex);
        /** @var Project $adjacent */
        $adjacent = $ordered->get($targetIndex);

        $currentOrder = $current->sort_order;
        $current->update(['sort_order' => $adjacent->sort_order]);
        $adjacent->update(['sort_order' => $currentOrder]);

        unset($this->projects);
    }

    private function deleteStoredFile(?string $path): void
    {
        if ($path !== null && $path !== '' && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function resetForm(): void
    {
        $this->resetValidation();
        $this->editingId = null;
        $this->title = '';
        $this->slug = '';
        $this->description = '';
        $this->techStackInput = '';
        $this->demo_url = null;
        $this->repo_url = null;
        $this->is_featured = true;
        $this->is_published = true;
        $this->sort_order = 0;
        $this->thumbnail = null;
        $this->existingThumbnailPath = null;
    }
}
