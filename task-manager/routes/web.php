<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Inertia\Inertia;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TimesheetController;
use App\Models\User;
use App\Models\Team;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Teams
    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');
    Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');
    Route::get('/teams/{team}', [TeamController::class, 'show']);

    // Projects
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}', [ProjectController::class, 'show']);

    // Tasks
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}', [TaskController::class, 'show']);

    // Timesheets
    Route::get('/timesheets', [TimesheetController::class, 'index'])->name('timesheets.index');
    Route::post('/timesheets', [TimesheetController::class, 'store'])->name('timesheets.store');
    Route::put('/timesheets/{id}', [TimesheetController::class, 'update'])->name('timesheets.update');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
