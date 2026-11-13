<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Admin\MessageInbox;
use App\Livewire\PublicPortfolio;
use App\Models\Message;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PublicPortfolioAndMessagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_portfolio_renders_all_seeded_sections(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee("Hi, I'm Nizar Khan")
            ->assertSee('Personal portfolio website')
            ->assertSee('Opensoft Technologies Sdn Bhd')
            ->assertSee('Laravel')
            ->assertSee('Get In Touch');
    }

    public function test_public_portfolio_navbar_shows_dashboard_when_authenticated(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('home'))
            ->assertOk()
            ->assertSee('Dashboard');
    }

    public function test_public_contact_form_validates_and_saves_message(): void
    {
        Livewire::test(PublicPortfolio::class)
            ->set('name', '')
            ->set('email', 'invalid-email')
            ->set('message', '')
            ->call('submitContact')
            ->assertHasErrors(['name', 'email', 'message']);

        Livewire::test(PublicPortfolio::class)
            ->set('name', 'Alex Tan')
            ->set('email', 'alex@example.com')
            ->set('subject', 'Project Collaboration')
            ->set('message', 'We would love to work with you on a Laravel app.')
            ->call('submitContact')
            ->assertHasNoErrors()
            ->assertSet('contactSent', true)
            ->assertSet('name', '')
            ->assertSet('email', '')
            ->assertSet('subject', '')
            ->assertSet('message', '');

        $this->assertDatabaseHas('messages', [
            'name' => 'Alex Tan',
            'email' => 'alex@example.com',
            'subject' => 'Project Collaboration',
            'is_read' => false,
        ]);
    }

    public function test_guests_cannot_access_admin_messages_page(): void
    {
        $this->get(route('admin.messages'))
            ->assertRedirect(route('login'));
    }

    public function test_admin_message_inbox_can_view_toggle_read_and_delete_messages(): void
    {
        $user = User::factory()->create();

        $msg = Message::query()->create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'subject' => 'Hiring Inquiry',
            'message' => 'Are you open to full-time roles?',
            'is_read' => false,
        ]);

        $this->actingAs($user)
            ->get(route('admin.messages'))
            ->assertOk()
            ->assertSee('Messages')
            ->assertSee('Jane Doe');

        $component = Livewire::actingAs($user)
            ->test(MessageInbox::class)
            ->call('viewMessage', $msg->id)
            ->assertSet('showViewModal', true)
            ->assertSet('viewingName', 'Jane Doe')
            ->assertSet('viewingIsRead', true);

        $this->assertTrue($msg->fresh()->is_read);

        $component->call('toggleRead', $msg->id);
        $this->assertFalse($msg->fresh()->is_read);

        $component
            ->call('confirmDelete', $msg->id)
            ->assertSet('showDeleteModal', true)
            ->call('delete')
            ->assertSet('showDeleteModal', false);

        $this->assertDatabaseMissing('messages', ['id' => $msg->id]);
    }

    public function test_database_seeder_seeds_two_messages_with_read_and_unread(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(2, Message::query()->count());
        $this->assertSame(1, Message::unread()->count());
        $this->assertSame(1, Message::query()->where('is_read', true)->count());
    }

    public function test_public_portfolio_shows_six_projects_in_grid_and_all_in_modal(): void
    {
        for ($i = 1; $i <= 8; $i++) {
            Project::query()->create([
                'title' => "Portfolio Project {$i}",
                'slug' => "portfolio-project-{$i}",
                'description' => "Description {$i}",
                'tech_stack' => ['Laravel'],
                'is_published' => true,
                'sort_order' => $i,
            ]);
        }

        $component = Livewire::test(PublicPortfolio::class)
            ->assertSee('Portfolio Project 1')
            ->assertSee('Portfolio Project 6')
            ->assertSee('View All Projects')
            ->set('showAllProjectsModal', true)
            ->assertSee('Portfolio Project 7')
            ->assertSee('Portfolio Project 8');

        $this->assertTrue($component->get('showAllProjectsModal'));
    }

    public function test_public_portfolio_can_filter_skills_by_category(): void
    {
        $this->seed(DatabaseSeeder::class);

        Livewire::test(PublicPortfolio::class)
            ->set('skillCategoryFilter', 'database')
            ->assertSee('MySQL')
            ->assertDontSee('Docker');
    }
}
