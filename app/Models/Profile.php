<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $name
 * @property string $headline
 * @property string $tagline
 * @property string|null $bio
 * @property string $location
 * @property bool $is_available
 * @property string|null $email
 * @property string|null $github_url
 * @property string|null $linkedin_url
 * @property string|null $resume_url
 * @property string|null $resume_path
 * @property string|null $avatar_path
 * @property string|null $banner_path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'name',
    'headline',
    'tagline',
    'bio',
    'location',
    'is_available',
    'email',
    'github_url',
    'linkedin_url',
    'resume_url',
    'resume_path',
    'avatar_path',
    'banner_path',
])]
class Profile extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_available' => 'boolean',
        ];
    }

    /**
     * Retrieve or initialize the singleton portfolio profile record.
     */
    public static function current(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Nizar Khan',
                'headline' => "Hi, I'm Nizar Khan",
                'tagline' => 'Fullstack PHP Developer — Laravel, Livewire, Filament, DevOps',
                'bio' => 'Fullstack PHP Developer experienced in building custom Laravel & Livewire applications, migrating legacy digital assets with 100% data integrity, optimizing MySQL queries, and managing production deployments.',
                'location' => 'Putrajaya, Malaysia',
                'is_available' => true,
            ],
        );
    }

    /**
     * Get the public URL for the profile avatar if uploaded.
     */
    public function avatarUrl(): ?string
    {
        return $this->avatar_path
            ? Storage::disk('public')->url($this->avatar_path)
            : null;
    }

    /**
     * Get the public URL for the hero banner if uploaded.
     */
    public function bannerUrl(): ?string
    {
        return $this->banner_path
            ? Storage::disk('public')->url($this->banner_path)
            : null;
    }

    /**
     * Resolve the active resume URL, preferring an uploaded PDF over the external URL.
     */
    public function resolvedResumeUrl(): ?string
    {
        if ($this->resume_path) {
            return Storage::disk('public')->url($this->resume_path);
        }

        return $this->resume_url ?: null;
    }
}
