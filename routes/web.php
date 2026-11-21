<?php

use App\Livewire\Admin\ExperienceSkillManager;
use App\Livewire\Admin\MessageInbox;
use App\Livewire\Admin\ProfileEditor;
use App\Livewire\Admin\ProjectManager;
use App\Livewire\PublicPortfolio;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

Route::get('/', PublicPortfolio::class)->name('home');

Route::get('/sitemap.xml', function (): Response {
    $timestamps = array_filter([
        Profile::query()->max('updated_at'),
        Project::query()->max('updated_at'),
        Experience::query()->max('updated_at'),
        Skill::query()->max('updated_at'),
    ]);

    $lastMod = $timestamps !== []
        ? Carbon::parse((string) max($timestamps))->toAtomString()
        : now()->toAtomString();

    $loc = htmlspecialchars(url('/'), ENT_XML1 | ENT_QUOTES, 'UTF-8');

    $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{$loc}</loc>
        <lastmod>{$lastMod}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
</urlset>
XML;

    return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
})->name('sitemap');

Route::get('/robots.txt', function (): Response {
    $sitemapUrl = url('/sitemap.xml');

    $content = <<<TXT
User-agent: *
Allow: /
Disallow: /admin/
Disallow: /dashboard
Disallow: /settings/
Disallow: /livewire/

Sitemap: {$sitemapUrl}
TXT;

    return response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
})->name('robots');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('admin/profile', ProfileEditor::class)->name('admin.profile');
    Route::get('admin/projects', ProjectManager::class)->name('admin.projects');
    Route::get('admin/experience-skills', ExperienceSkillManager::class)->name('admin.experience-skills');
    Route::get('admin/messages', MessageInbox::class)->name('admin.messages');
});

require __DIR__.'/settings.php';
