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
        $projects = [
            [
                'slug' => 'personal-portfolio-website',
                'title' => 'Personal Portfolio Website',
                'description' => 'Cyberpunk HUD personal portfolio and full-featured admin CMS built on Laravel 13, Livewire 4, Flux UI, Alpine.js, and Tailwind CSS v4, featuring profile & hero management, project showcase with thumbnail uploads, career experience & categorized skills, contact inbox with email notifications, 2FA authentication, and CI/CD via GitHub Actions.',
                'tech_stack' => ['Laravel', 'Livewire', 'Tailwind CSS', 'Alpine JS', 'PHP', 'MySQL', 'HTML', 'CSS', 'GitHub'],
                'thumbnail_path' => 'projects/thumbnails/personal-portfolio-website.png',
                'demo_url' => '#',
                'repo_url' => null,
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 1,
            ],
            [
                'slug' => 'life-os',
                'title' => 'LifeOS — Personal Command Center',
                'description' => 'Self-hosted personal productivity and autonomous command center built with Laravel 13, Livewire 4, Flux UI, and Tailwind CSS v4. Consolidates tasks & habits, goals & milestones, wallets/budgets/transactions, recurring subscriptions, meetings with reminders, brain dumps, weekly reviews, and an automated Telegram briefing engine into a single dashboard.',
                'tech_stack' => ['Laravel', 'Livewire', 'Flux UI', 'Tailwind CSS', 'PHP', 'MySQL', 'Fortify', 'Telegram Bot'],
                'thumbnail_path' => 'projects/thumbnails/q10K1FYPUmi8aPXzTryPVqr6okD3PV1cfZNg5VDS.png',
                'demo_url' => '#',
                'repo_url' => null,
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(
                ['slug' => $project['slug']],
                $project,
            );
        }
    }
}
