<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Experience;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class ExperienceSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $experiences = [
            [
                'role' => 'IT Developer',
                'company' => 'Opensoft Technologies Sdn Bhd',
                'location' => 'Putrajaya, Malaysia',
                'period' => 'February 2025 - Present',
                'description' => 'I successfully migrated digital assets from legacy platforms with 100% data accuracy and integrity, while enhancing website sections like galleries and news to align with stakeholder needs. I performed precise data entry following strict formatting and validation protocols, designed responsive layouts using HTML, CSS, Bootstrap, and JavaScript, and developed backend functionality with Yii2 to improve system scalability. Additionally, I built and maintained a custom Laravel-based CMS for non-technical teams, handled secure code patching and updates on production servers via VPN and onsite deployment, and actively participated in UAT sessions to translate feedback into technical requirements, ensuring seamless project handovers and compliance with new system standards.',
                'tech_stack' => ['Livewire', 'Bootstrap', 'Tailwind CSS', 'Alpine JS', 'GitHub', 'Filament', 'Laravel', 'MySQL', 'JQuery', 'JavaScript', 'CSS', 'HTML', 'PHP', 'Yii2', 'DevOps'],
                'sort_order' => 1,
            ],
            [
                'role' => 'Junior Web Programmer',
                'company' => 'Intesh Synergy Sdn Bhd',
                'location' => 'Cyberjaya, Selangor',
                'period' => 'December 2023 – January 2025',
                'description' => 'Maintained company website using Laravel, corrected 70% of inaccurate dashboard data, improved system performance by 5% through optimized MySQL queries, and reduced manual data entry by 20% with an export-to-Excel feature.',
                'tech_stack' => ['Laravel', 'PHP', 'HTML', 'CSS', 'JavaScript', 'JQuery', 'MySQL', 'JIRA', 'Bootstrap', 'Tailwind CSS'],
                'sort_order' => 2,
            ],
            [
                'role' => 'Web Developer (Internship)',
                'company' => 'Malaysia Research Institute For Professional Education And Training',
                'location' => 'Parit Raja, Johor',
                'period' => 'September 2022 – February 2023',
                'description' => 'Developed a researcher registration website using PHP/MySQL, performed system analysis (ERD, DFD, relational schema), and created multimedia content including corporate videos, certificate templates, and book covers.',
                'tech_stack' => ['HTML', 'CSS', 'JavaScript', 'JQuery', 'MySQL', 'Bootstrap', 'PHP'],
                'sort_order' => 3,
            ],
        ];

        foreach ($experiences as $experience) {
            Experience::updateOrCreate(
                [
                    'role' => $experience['role'],
                    'company' => $experience['company'],
                ],
                $experience,
            );
        }

        $skills = [
            ['name' => 'PHP', 'category' => 'backend', 'sort_order' => 1],
            ['name' => 'Laravel', 'category' => 'backend', 'sort_order' => 2],
            ['name' => 'Livewire', 'category' => 'backend', 'sort_order' => 3],
            ['name' => 'Filament', 'category' => 'backend', 'sort_order' => 4],
            ['name' => 'Python', 'category' => 'backend', 'sort_order' => 5],
            ['name' => 'MySQL', 'category' => 'database', 'sort_order' => 6],
            ['name' => 'JavaScript', 'category' => 'frontend', 'sort_order' => 7],
            ['name' => 'JQuery', 'category' => 'frontend', 'sort_order' => 8],
            ['name' => 'Alpine JS', 'category' => 'frontend', 'sort_order' => 9],
            ['name' => 'Tailwind CSS', 'category' => 'frontend', 'sort_order' => 10],
            ['name' => 'Bootstrap', 'category' => 'frontend', 'sort_order' => 11],
            ['name' => 'HTML', 'category' => 'frontend', 'sort_order' => 12],
            ['name' => 'CSS', 'category' => 'frontend', 'sort_order' => 13],
            ['name' => 'Docker', 'category' => 'devops_tools', 'sort_order' => 14],
            ['name' => 'GitHub', 'category' => 'devops_tools', 'sort_order' => 15],
            ['name' => 'JIRA', 'category' => 'devops_tools', 'sort_order' => 16],
            ['name' => 'Postman', 'category' => 'devops_tools', 'sort_order' => 17],
            ['name' => 'Test', 'category' => 'devops_tools', 'sort_order' => 18],
        ];

        foreach ($skills as $skill) {
            Skill::updateOrCreate(
                ['name' => $skill['name']],
                $skill,
            );
        }
    }
}
