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
                'tagline' => 'Fullstack PHP Developer — Laravel, Livewire, Filament, DevOps',
                'bio' => 'Fullstack PHP Developer experienced in building custom Laravel & Livewire applications, migrating legacy digital assets with 100% data integrity, optimizing MySQL queries, and managing production deployments.',
                'location' => 'Putrajaya, Malaysia',
                'is_available' => true,
            ],
        );
    }
}
