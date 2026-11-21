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
                'thumbnail_path' => 'projects/thumbnails/DRxc9qQiRypzmMKPW2h7UD3wAFKIF5XrrZbQlZf9.png',
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
                'thumbnail_path' => 'projects/thumbnails/v8l664jAIJhIXXMNDwkAG3mRxakWECjjx102DKVe.png',
                'demo_url' => '#',
                'repo_url' => null,
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 2,
            ],
            [
                'slug' => 'kanban-pmo',
                'title' => 'Kanban PMO — Project Command Center',
                'description' => 'Interactive project management and PMO command center built with Laravel 13, Livewire 4, Flux UI, Tailwind CSS v4, and the Anthropic Claude SDK. Features a drag-and-drop Kanban board with WIP limits, project & team PIC roster, rich tasks with subtasks and Ctrl+K search, live analytics & daily standup reports, an AI PMO assistant, and omnichannel automation across Telegram, Gmail, and WhatsApp.',
                'tech_stack' => ['Laravel', 'Livewire', 'Flux UI', 'Tailwind CSS', 'PHP', 'MySQL', 'Anthropic AI', 'Telegram Bot'],
                'thumbnail_path' => 'projects/thumbnails/o2Q3Lzx4jVxhIwlP8As0eDhyjiUiw69UFKbZCbY3.png',
                'demo_url' => 'http://127.0.0.1:8002/',
                'repo_url' => null,
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 3,
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
