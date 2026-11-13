<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Profile;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Layout('layouts::app')]
#[Title('Profile & Hero')]
class ProfileEditor extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $headline = '';

    public string $tagline = '';

    public ?string $bio = null;

    public string $location = '';

    public bool $is_available = true;

    public ?string $email = null;

    public ?string $github_url = null;

    public ?string $linkedin_url = null;

    public ?string $resume_url = null;

    public ?TemporaryUploadedFile $avatar = null;

    public ?TemporaryUploadedFile $banner = null;

    public ?TemporaryUploadedFile $resumeFile = null;

    public ?string $existingAvatarPath = null;

    public ?string $existingBannerPath = null;

    public ?string $existingResumePath = null;

    /**
     * Mount the component with the singleton portfolio profile.
     */
    public function mount(): void
    {
        $profile = Profile::current();

        $this->name = $profile->name;
        $this->headline = $profile->headline;
        $this->tagline = $profile->tagline;
        $this->bio = $profile->bio;
        $this->location = $profile->location;
        $this->is_available = $profile->is_available;
        $this->email = $profile->email;
        $this->github_url = $profile->github_url;
        $this->linkedin_url = $profile->linkedin_url;
        $this->resume_url = $profile->resume_url;
        $this->existingAvatarPath = $profile->avatar_path;
        $this->existingBannerPath = $profile->banner_path;
        $this->existingResumePath = $profile->resume_path;
    }

    /**
     * Save the portfolio profile, hero settings, and uploaded media.
     */
    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'headline' => ['required', 'string', 'max:255'],
            'tagline' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'location' => ['required', 'string', 'max:255'],
            'is_available' => ['required', 'boolean'],
            'email' => ['nullable', 'string', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:500'],
            'linkedin_url' => ['nullable', 'url', 'max:500'],
            'resume_url' => ['nullable', 'url', 'max:500'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'banner' => ['nullable', 'image', 'max:4096'],
            'resumeFile' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        unset($validated['avatar'], $validated['banner'], $validated['resumeFile']);

        foreach (['bio', 'email', 'github_url', 'linkedin_url', 'resume_url'] as $nullableField) {
            if ($validated[$nullableField] === '') {
                $validated[$nullableField] = null;
            }
        }

        if ($this->avatar instanceof TemporaryUploadedFile) {
            $this->existingAvatarPath = $this->replaceStoredFile(
                $this->avatar,
                $this->existingAvatarPath,
                'profile/avatars',
            );
            $validated['avatar_path'] = $this->existingAvatarPath;
            $this->avatar = null;
        }

        if ($this->banner instanceof TemporaryUploadedFile) {
            $this->existingBannerPath = $this->replaceStoredFile(
                $this->banner,
                $this->existingBannerPath,
                'profile/banners',
            );
            $validated['banner_path'] = $this->existingBannerPath;
            $this->banner = null;
        }

        if ($this->resumeFile instanceof TemporaryUploadedFile) {
            $this->existingResumePath = $this->replaceStoredFile(
                $this->resumeFile,
                $this->existingResumePath,
                'profile/resumes',
            );
            $validated['resume_path'] = $this->existingResumePath;
            $this->resumeFile = null;
        }

        Profile::current()->update($validated);

        Flux::toast(variant: 'success', text: 'Profile and hero settings updated.');
    }

    /**
     * Toggle the availability status with a single click.
     */
    public function toggleAvailability(): void
    {
        $this->is_available = ! $this->is_available;

        Profile::current()->update([
            'is_available' => $this->is_available,
        ]);

        Flux::toast(
            variant: 'success',
            text: $this->is_available
                ? 'Availability status set to Available for Work.'
                : 'Availability status set to Unavailable.',
        );
    }

    /**
     * Remove the saved profile avatar.
     */
    public function removeAvatar(): void
    {
        $this->deleteStoredFile($this->existingAvatarPath);
        $this->existingAvatarPath = null;
        $this->avatar = null;

        Profile::current()->update(['avatar_path' => null]);

        Flux::toast(variant: 'success', text: 'Profile avatar removed.');
    }

    /**
     * Remove the saved hero banner to revert to the default CSS background.
     */
    public function removeBanner(): void
    {
        $this->deleteStoredFile($this->existingBannerPath);
        $this->existingBannerPath = null;
        $this->banner = null;

        Profile::current()->update(['banner_path' => null]);

        Flux::toast(variant: 'success', text: 'Hero banner removed. Default CSS background active.');
    }

    /**
     * Remove the uploaded resume PDF.
     */
    public function removeResumeFile(): void
    {
        $this->deleteStoredFile($this->existingResumePath);
        $this->existingResumePath = null;
        $this->resumeFile = null;

        Profile::current()->update(['resume_path' => null]);

        Flux::toast(variant: 'success', text: 'Uploaded resume PDF removed.');
    }

    public function render(): View
    {
        return view('livewire.admin.profile-editor');
    }

    private function replaceStoredFile(TemporaryUploadedFile $file, ?string $existingPath, string $directory): string
    {
        $this->deleteStoredFile($existingPath);

        $storedPath = $file->store($directory, 'public');

        return is_string($storedPath) ? $storedPath : '';
    }

    private function deleteStoredFile(?string $path): void
    {
        if ($path !== null && $path !== '' && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
