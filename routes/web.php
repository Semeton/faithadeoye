<?php

use App\Http\Middleware\TrackPageView;
use Illuminate\Support\Facades\Route;

// Public portfolio routes
Route::middleware([TrackPageView::class])->group(function () {
    Route::livewire('/', 'pages::home')->name('home');
    Route::livewire('/projects', 'pages::projects')->name('projects');
    Route::livewire('/projects/{slug}', 'pages::project-detail')->name('project.show');
});

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::livewire('/', 'pages::admin.dashboard')->name('dashboard');
    Route::livewire('/content', 'pages::admin.content')->name('content');
    Route::livewire('/projects', 'pages::admin.projects')->name('projects');
    Route::livewire('/milestones', 'pages::admin.milestones')->name('milestones');
    Route::livewire('/impact-areas', 'pages::admin.impact-areas')->name('impact-areas');
    Route::livewire('/messages', 'pages::admin.messages')->name('messages');
    Route::livewire('/analytics', 'pages::admin.analytics')->name('analytics');
});

// Fortify auth routes redirect authenticated users away from dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    Route::redirect('dashboard', '/admin')->name('dashboard');
});

require __DIR__.'/settings.php';
