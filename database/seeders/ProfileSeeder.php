<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profile::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Nizar Khan',
                'headline' => "Hi, I'm Nizar Khan",
                'tagline' => 'Fullstack PHP Developer · Laravel, Livewire & Filament',
                'bio' => 'I design and ship reliable web platforms, admin dashboards, and automated workflows — turning complex business processes and legacy databases into fast, production-ready systems.',
                'location' => 'Kuala Lumpur, Malaysia',
                'is_available' => true,
                'email' => 'nizarkhan.digital@gmail.com',
                'github_url' => 'https://github.com/NizarKhan0',
                'linkedin_url' => null,
                'resume_url' => null,
                'avatar_path' => 'profile/avatars/AeOnkSqElXZqqCcT1HqPw7ygrQr3jxIvHwbVf3oZ.jpg',
            ],
        );
    }
}
