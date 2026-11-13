<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Livewire\Admin\ProfileEditor;
use App\Models\Profile;
use App\Models\User;
use Database\Seeders\ProfileSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileEditorTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_admin_profile_page(): void
    {
        $this->get(route('admin.profile'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_dashboard_and_admin_profile(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Portfolio Overview')
            ->assertSee('Total Projects')
            ->assertSee('Unread Messages');

        $this->actingAs($user)
            ->get(route('admin.profile'))
            ->assertOk()
            ->assertSee('Profile & Hero');
    }

    public function test_profile_current_returns_singleton_with_boolean_cast(): void
    {
        $profile = Profile::current();

        $this->assertSame(1, $profile->id);
        $this->assertSame('Nizar Khan', $profile->name);
        $this->assertTrue($profile->is_available);
        $this->assertSame(1, Profile::count());
    }

    public function test_profile_editor_can_update_hero_and_social_links(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(ProfileEditor::class)
            ->set('name', 'Nizar Khan Updated')
            ->set('headline', 'Building Scalable Laravel Apps')
            ->set('tagline', 'Principal PHP & Livewire Engineer')
            ->set('bio', 'Experienced in Laravel, Livewire, and DevOps.')
            ->set('location', 'Kuala Lumpur, Malaysia')
            ->set('email', 'nizar@zaryue.com')
            ->set('github_url', 'https://github.com/nizarkhan')
            ->set('linkedin_url', 'https://linkedin.com/in/nizarkhan')
            ->set('resume_url', 'https://zaryue.com/resume.pdf')
            ->call('save')
            ->assertHasNoErrors();

        $profile = Profile::current();

        $this->assertSame('Nizar Khan Updated', $profile->name);
        $this->assertSame('Building Scalable Laravel Apps', $profile->headline);
        $this->assertSame('Principal PHP & Livewire Engineer', $profile->tagline);
        $this->assertSame('Kuala Lumpur, Malaysia', $profile->location);
        $this->assertSame('nizar@zaryue.com', $profile->email);
        $this->assertSame('https://github.com/nizarkhan', $profile->github_url);
        $this->assertSame('https://linkedin.com/in/nizarkhan', $profile->linkedin_url);
        $this->assertSame('https://zaryue.com/resume.pdf', $profile->resume_url);
    }

    public function test_profile_editor_can_toggle_availability_with_one_click(): void
    {
        $user = User::factory()->create();
        $profile = Profile::current();
        $this->assertTrue($profile->is_available);

        Livewire::actingAs($user)
            ->test(ProfileEditor::class)
            ->call('toggleAvailability')
            ->assertSet('is_available', false);

        $this->assertFalse(Profile::current()->is_available);

        Livewire::actingAs($user)
            ->test(ProfileEditor::class)
            ->call('toggleAvailability')
            ->assertSet('is_available', true);

        $this->assertTrue(Profile::current()->is_available);
    }

    public function test_profile_editor_validates_fields(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(ProfileEditor::class)
            ->set('name', '')
            ->set('email', 'not-an-email')
            ->set('github_url', 'not-a-url')
            ->call('save')
            ->assertHasErrors(['name', 'email', 'github_url']);
    }

    public function test_profile_seeder_seeds_expected_singleton_record(): void
    {
        $this->seed(ProfileSeeder::class);

        $profile = Profile::findOrFail(1);

        $this->assertSame('Nizar Khan', $profile->name);
        $this->assertSame("Hi, I'm Nizar Khan", $profile->headline);
        $this->assertSame('Fullstack PHP Developer — Laravel, Livewire, Filament, DevOps', $profile->tagline);
        $this->assertSame('Putrajaya, Malaysia', $profile->location);
        $this->assertTrue($profile->is_available);
    }

    public function test_profile_editor_can_upload_and_remove_avatar_and_banner(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $avatar = UploadedFile::fake()->image('avatar.jpg', 400, 400);
        $banner = UploadedFile::fake()->image('banner.png', 1600, 600);

        $component = Livewire::actingAs($user)
            ->test(ProfileEditor::class)
            ->set('avatar', $avatar)
            ->set('banner', $banner)
            ->call('save')
            ->assertHasNoErrors();

        $profile = Profile::current();

        $this->assertNotNull($profile->avatar_path);
        $this->assertNotNull($profile->banner_path);
        Storage::disk('public')->assertExists($profile->avatar_path);
        Storage::disk('public')->assertExists($profile->banner_path);

        $oldAvatar = $profile->avatar_path;
        $oldBanner = $profile->banner_path;

        $component->call('removeAvatar')->call('removeBanner');

        $profile->refresh();

        $this->assertNull($profile->avatar_path);
        $this->assertNull($profile->banner_path);
        Storage::disk('public')->assertMissing($oldAvatar);
        Storage::disk('public')->assertMissing($oldBanner);
    }

    public function test_profile_editor_validates_uploaded_media_must_be_images(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $invalidFile = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        Livewire::actingAs($user)
            ->test(ProfileEditor::class)
            ->set('avatar', $invalidFile)
            ->set('banner', $invalidFile)
            ->call('save')
            ->assertHasErrors(['avatar', 'banner']);
    }

    public function test_profile_editor_can_upload_and_remove_resume_pdf_with_fallback_url(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $pdf = UploadedFile::fake()->create('nizar-resume.pdf', 512, 'application/pdf');

        $component = Livewire::actingAs($user)
            ->test(ProfileEditor::class)
            ->set('resume_url', 'https://zaryue.com/resume.pdf')
            ->set('resumeFile', $pdf)
            ->call('save')
            ->assertHasNoErrors();

        $profile = Profile::current();

        $this->assertNotNull($profile->resume_path);
        Storage::disk('public')->assertExists($profile->resume_path);
        $this->assertSame(Storage::disk('public')->url($profile->resume_path), $profile->resolvedResumeUrl());

        $oldResume = $profile->resume_path;

        $component->call('removeResumeFile');
        $profile->refresh();

        $this->assertNull($profile->resume_path);
        Storage::disk('public')->assertMissing($oldResume);
        $this->assertSame('https://zaryue.com/resume.pdf', $profile->resolvedResumeUrl());
    }

    public function test_profile_editor_rejects_non_pdf_resume_file(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $image = UploadedFile::fake()->image('resume.png');

        Livewire::actingAs($user)
            ->test(ProfileEditor::class)
            ->set('resumeFile', $image)
            ->call('save')
            ->assertHasErrors(['resumeFile']);
    }
}
