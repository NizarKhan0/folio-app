<?php

use App\Livewire\Admin\ProfileEditor;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('admin/profile', ProfileEditor::class)->name('admin.profile');
});

require __DIR__.'/settings.php';
