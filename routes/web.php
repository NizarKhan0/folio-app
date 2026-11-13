<?php

use App\Livewire\Admin\ExperienceSkillManager;
use App\Livewire\Admin\MessageInbox;
use App\Livewire\Admin\ProfileEditor;
use App\Livewire\Admin\ProjectManager;
use App\Livewire\PublicPortfolio;
use Illuminate\Support\Facades\Route;

Route::get('/', PublicPortfolio::class)->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('admin/profile', ProfileEditor::class)->name('admin.profile');
    Route::get('admin/projects', ProjectManager::class)->name('admin.projects');
    Route::get('admin/experience-skills', ExperienceSkillManager::class)->name('admin.experience-skills');
    Route::get('admin/messages', MessageInbox::class)->name('admin.messages');
});

require __DIR__.'/settings.php';
