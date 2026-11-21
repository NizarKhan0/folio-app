<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Livewire\Admin\ExperienceSkillManager;
use App\Models\Experience;
use App\Models\Skill;
use App\Models\User;
use Database\Seeders\ExperienceSkillSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ExperienceSkillManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_experience_skills_page(): void
    {
        $this->get(route('admin.experience-skills'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_experience_skills_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.experience-skills'))
            ->assertOk()
            ->assertSee('Experience & Skills')
            ->assertSee('Add Experience');
    }

    public function test_experience_crud_and_reordering_work(): void
    {
        $user = User::factory()->create();

        $component = Livewire::actingAs($user)
            ->test(ExperienceSkillManager::class)
            ->call('openCreateExperienceModal')
            ->set('role', 'Senior PHP Developer')
            ->set('company', 'Zaryue Labs')
            ->set('location', 'Putrajaya, Malaysia')
            ->set('period', 'January 2026 - Present')
            ->set('description', 'Leading Laravel architecture.')
            ->set('selectedTechStack', ['Laravel', 'Livewire', 'MySQL'])
            ->call('saveExperience')
            ->assertHasNoErrors();

        $first = Experience::query()->where('company', 'Zaryue Labs')->firstOrFail();
        $this->assertSame('Z', $first->companyInitial());
        $this->assertSame(['Laravel', 'Livewire', 'MySQL'], $first->tech_stack);

        $second = Experience::query()->create([
            'role' => 'Backend Engineer',
            'company' => 'Acme Corp',
            'location' => 'KL',
            'period' => '2024 - 2025',
            'description' => 'Built APIs.',
            'tech_stack' => ['PHP'],
            'sort_order' => 2,
        ]);

        $component->call('moveExperienceDown', $first->id);
        $this->assertSame(2, $first->fresh()->sort_order);
        $this->assertSame(1, $second->fresh()->sort_order);

        $component
            ->call('openEditExperienceModal', $first->id)
            ->set('role', 'Principal PHP Developer')
            ->call('saveExperience')
            ->assertHasNoErrors();

        $this->assertSame('Principal PHP Developer', $first->fresh()->role);

        $component
            ->call('confirmDelete', 'experience', $first->id)
            ->assertSet('showDeleteModal', true)
            ->call('delete')
            ->assertSet('showDeleteModal', false);

        $this->assertDatabaseMissing('experiences', ['id' => $first->id]);
    }

    public function test_skill_crud_and_reordering_work(): void
    {
        $user = User::factory()->create();

        $component = Livewire::actingAs($user)
            ->test(ExperienceSkillManager::class)
            ->set('activeTab', 'skills')
            ->call('openCreateSkillModal')
            ->set('skillName', 'Redis')
            ->set('skillCategory', 'database')
            ->call('saveSkill')
            ->assertHasNoErrors();

        $first = Skill::query()->where('name', 'Redis')->firstOrFail();
        $this->assertSame('Database', $first->categoryLabel());

        $second = Skill::query()->create([
            'name' => 'Nginx',
            'category' => 'devops_tools',
            'sort_order' => 2,
        ]);

        $component->call('moveSkillDown', $first->id);
        $this->assertSame(2, $first->fresh()->sort_order);
        $this->assertSame(1, $second->fresh()->sort_order);

        $component
            ->call('openEditSkillModal', $first->id)
            ->set('skillName', 'Redis Cache')
            ->set('skillCategory', 'devops_tools')
            ->call('saveSkill')
            ->assertHasNoErrors();

        $this->assertSame('Redis Cache', $first->fresh()->name);
        $this->assertSame('devops_tools', $first->fresh()->category);

        $component
            ->call('confirmDelete', 'skill', $first->id)
            ->assertSet('showDeleteModal', true)
            ->call('delete')
            ->assertSet('showDeleteModal', false);

        $this->assertDatabaseMissing('skills', ['id' => $first->id]);
    }

    public function test_experience_skill_seeder_seeds_real_data(): void
    {
        $this->seed(ExperienceSkillSeeder::class);

        $this->assertSame(3, Experience::query()->count());
        $this->assertSame(27, Skill::query()->count());

        $opensoft = Experience::query()->where('company', 'Opensoft Technologies Sdn Bhd')->firstOrFail();
        $intesh = Experience::query()->where('company', 'Intesh Synergy Sdn Bhd')->firstOrFail();
        $mripet = Experience::query()->where('company', 'Malaysia Research Institute For Professional Education And Training')->firstOrFail();

        $this->assertSame('O', $opensoft->companyInitial());
        $this->assertSame('I', $intesh->companyInitial());
        $this->assertSame('M', $mripet->companyInitial());
        $this->assertTrue(Skill::query()->where('name', 'Laravel')->exists());
        $this->assertTrue(Skill::query()->where('name', 'Docker')->exists());
    }
}
