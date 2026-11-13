<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::updateOrCreate(
            ['slug' => 'personal-portfolio-website'],
            [
                'title' => 'Personal portfolio website',
                'description' => 'Single-page portfolio with admin dashboard built on Laravel + Livewire + Flux UI, featuring CRUD content management, authentication with 2FA, drag-free sorting, image upload, contact form with email notification, and CI/CD via GitHub Actions.',
                'tech_stack' => ['Livewire', 'Tailwind CSS', 'CSS', 'PHP', 'HTML', 'MySQL', 'Laravel', 'GitHub'],
                'demo_url' => 'https://me.zaryue.com/',
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 1,
            ],
        );
    }
}
