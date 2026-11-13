<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Livewire\Admin\ProjectManager;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\ProjectSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProjectManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_admin_projects_page(): void
    {
        $this->get(route('admin.projects'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_projects_page_and_sidebar_link(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.projects'))
            ->assertOk()
            ->assertSee('Projects')
            ->assertSee('Add Project');
    }

    public function test_project_manager_can_create_project_with_thumbnail_and_tech_stack(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $thumbnail = UploadedFile::fake()->image('thumb.png', 800, 500);

        Livewire::actingAs($user)
            ->test(ProjectManager::class)
            ->call('openCreateModal')
            ->set('title', 'Zaryue Cloud Billing')
            ->set('description', 'Multi-tenant billing platform built on Laravel.')
            ->set('selectedTechStack', ['Laravel', 'Livewire', 'MySQL', 'Laravel'])
            ->set('demo_url', 'https://billing.zaryue.com')
            ->set('repo_url', 'https://github.com/nizarkhan/billing')
            ->set('thumbnail', $thumbnail)
            ->set('is_featured', true)
            ->set('is_published', true)
            ->call('save')
            ->assertHasNoErrors();

        $project = Project::query()->where('slug', 'zaryue-cloud-billing')->firstOrFail();

        $this->assertSame('Zaryue Cloud Billing', $project->title);
        $this->assertSame(['Laravel', 'Livewire', 'MySQL'], $project->tech_stack);
        $this->assertNotNull($project->thumbnail_path);
        Storage::disk('public')->assertExists($project->thumbnail_path);
    }

    public function test_project_manager_can_update_project_and_toggle_published(): void
    {
        $user = User::factory()->create();
        $project = Project::query()->create([
            'title' => 'Old Title',
            'slug' => 'old-title',
            'description' => 'Initial description.',
            'tech_stack' => ['PHP'],
            'is_featured' => false,
            'is_published' => true,
            'sort_order' => 1,
        ]);

        Livewire::actingAs($user)
            ->test(ProjectManager::class)
            ->call('openEditModal', $project->id)
            ->set('title', 'New Title')
            ->set('slug', 'new-title')
            ->set('selectedTechStack', ['PHP', 'Laravel'])
            ->call('save')
            ->assertHasNoErrors()
            ->call('togglePublished', $project->id);

        $project->refresh();

        $this->assertSame('New Title', $project->title);
        $this->assertSame('new-title', $project->slug);
        $this->assertSame(['PHP', 'Laravel'], $project->tech_stack);
        $this->assertFalse($project->is_published);
    }

    public function test_project_manager_can_reorder_projects_up_and_down(): void
    {
        $user = User::factory()->create();

        $first = Project::query()->create([
            'title' => 'First Project',
            'slug' => 'first-project',
            'description' => 'First.',
            'tech_stack' => ['PHP'],
            'sort_order' => 1,
        ]);

        $second = Project::query()->create([
            'title' => 'Second Project',
            'slug' => 'second-project',
            'description' => 'Second.',
            'tech_stack' => ['Laravel'],
            'sort_order' => 2,
        ]);

        Livewire::actingAs($user)
            ->test(ProjectManager::class)
            ->call('moveDown', $first->id);

        $this->assertSame(2, $first->fresh()->sort_order);
        $this->assertSame(1, $second->fresh()->sort_order);

        Livewire::actingAs($user)
            ->test(ProjectManager::class)
            ->call('moveUp', $first->id);

        $this->assertSame(1, $first->fresh()->sort_order);
        $this->assertSame(2, $second->fresh()->sort_order);
    }

    public function test_project_manager_can_delete_project_via_confirmation_modal(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $path = UploadedFile::fake()->image('cover.jpg')->store('projects/thumbnails', 'public');

        $project = Project::query()->create([
            'title' => 'Delete Me',
            'slug' => 'delete-me',
            'description' => 'To be removed.',
            'tech_stack' => ['Laravel'],
            'thumbnail_path' => is_string($path) ? $path : null,
            'sort_order' => 1,
        ]);

        Livewire::actingAs($user)
            ->test(ProjectManager::class)
            ->call('confirmDelete', $project->id)
            ->assertSet('showDeleteModal', true)
            ->assertSet('deletingTitle', 'Delete Me')
            ->call('delete')
            ->assertSet('showDeleteModal', false);

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        if (is_string($path)) {
            Storage::disk('public')->assertMissing($path);
        }
    }

    public function test_project_scope_published_filters_and_orders_correctly(): void
    {
        Project::query()->create([
            'title' => 'Draft Project',
            'slug' => 'draft-project',
            'description' => 'Hidden.',
            'tech_stack' => ['PHP'],
            'is_published' => false,
            'sort_order' => 1,
        ]);

        Project::query()->create([
            'title' => 'Second Published',
            'slug' => 'second-published',
            'description' => 'Visible second.',
            'tech_stack' => ['PHP'],
            'is_published' => true,
            'sort_order' => 2,
        ]);

        Project::query()->create([
            'title' => 'First Published',
            'slug' => 'first-published',
            'description' => 'Visible first.',
            'tech_stack' => ['Laravel'],
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $titles = Project::published()->pluck('title')->all();

        $this->assertSame(['First Published', 'Second Published'], $titles);
    }

    public function test_project_seeder_seeds_personal_portfolio_website(): void
    {
        $this->seed(ProjectSeeder::class);

        $project = Project::query()->where('slug', 'personal-portfolio-website')->firstOrFail();

        $this->assertSame('Personal Portfolio Website', $project->title);
        $this->assertSame('#', $project->demo_url);
        $this->assertTrue($project->is_featured);
        $this->assertTrue($project->is_published);
        $this->assertSame(1, $project->sort_order);
        $this->assertContains('Livewire', $project->tech_stack);
    }

    public function test_project_manager_enforces_maximum_six_featured_projects(): void
    {
        $user = User::factory()->create();

        for ($i = 1; $i <= 6; $i++) {
            Project::query()->create([
                'title' => "Featured {$i}",
                'slug' => "featured-{$i}",
                'description' => "Featured description {$i}.",
                'tech_stack' => ['Laravel'],
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => $i,
            ]);
        }

        $seventh = Project::query()->create([
            'title' => 'Seventh Project',
            'slug' => 'seventh-project',
            'description' => 'Not featured initially.',
            'tech_stack' => ['PHP'],
            'is_featured' => false,
            'is_published' => true,
            'sort_order' => 7,
        ]);

        // Creating a 7th featured project should fail validation on is_featured.
        Livewire::actingAs($user)
            ->test(ProjectManager::class)
            ->call('openCreateModal')
            ->assertSet('is_featured', false)
            ->set('title', 'Eighth Project')
            ->set('description', 'Attempt to exceed max featured.')
            ->set('selectedTechStack', ['Laravel'])
            ->set('is_featured', true)
            ->call('save')
            ->assertHasErrors(['is_featured']);

        // Toggling featured on a 7th project should not exceed 6 featured projects.
        Livewire::actingAs($user)
            ->test(ProjectManager::class)
            ->call('toggleFeatured', $seventh->id);

        $this->assertFalse($seventh->fresh()->is_featured);

        // Unfeaturing one project allows featuring the 7th project.
        $first = Project::query()->where('slug', 'featured-1')->firstOrFail();

        Livewire::actingAs($user)
            ->test(ProjectManager::class)
            ->call('toggleFeatured', $first->id)
            ->call('toggleFeatured', $seventh->id);

        $this->assertFalse($first->fresh()->is_featured);
        $this->assertTrue($seventh->fresh()->is_featured);
    }
}
