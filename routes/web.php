<?php

use App\Livewire\Admin\ProfileEditor;
use App\Livewire\Admin\ProjectManager;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('admin/profile', ProfileEditor::class)->name('admin.profile');
    Route::get('admin/projects', ProjectManager::class)->name('admin.projects');
});

require __DIR__.'/settings.php';
