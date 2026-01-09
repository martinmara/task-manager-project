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

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
    ]);
});

/*
|--------------------------------------------------------------------------
| Authenticated users (ALL roles)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Teams (view only)
    Route::get('/teams', [TeamController::class, 'index'])
        ->name('teams.index');
    Route::get('/teams/{team}', [TeamController::class, 'show'])
        ->name('teams.show');

    // Projects (view only)
    Route::get('/projects', [ProjectController::class, 'index'])
        ->name('projects.index');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])
        ->name('projects.show');

    // Tasks (view only)
    Route::get('/tasks', [TaskController::class, 'index'])
        ->name('tasks.index');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])
        ->name('tasks.show');

    // Timesheets (all authenticated users)
    Route::get('/timesheets', [TimesheetController::class, 'index'])
        ->name('timesheets.index');
    Route::post('/timesheets', [TimesheetController::class, 'store'])
        ->name('timesheets.store');
    Route::put('/timesheets/{id}', [TimesheetController::class, 'update'])
        ->name('timesheets.update');

    // Task comments
    Route::put('/tasks/{id}/comment', [TaskController::class, 'updateComment'])
        ->name('tasks.comment');
});

/*
|--------------------------------------------------------------------------
| ADMIN-ONLY routes
|--------------------------------------------------------------------------
| Requires role "Admin"
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'admin'])->group(function () {

    // Teams (create / update / delete)
    Route::post('/teams', [TeamController::class, 'store'])
        ->name('teams.store');
    Route::put('/teams/{team}', [TeamController::class, 'update'])
        ->name('teams.update');
    Route::delete('/teams/{team}', [TeamController::class, 'destroy'])
        ->name('teams.destroy');

    // Projects (create / delete)
    Route::post('/projects', [ProjectController::class, 'store'])
        ->name('projects.store');
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])
        ->name('projects.destroy');

    // Tasks (create / complete / delete)
    Route::post('/tasks', [TaskController::class, 'store'])
        ->name('tasks.store');
    Route::put('/tasks/{id}/complete', [TaskController::class, 'complete'])
        ->name('tasks.complete');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])
        ->name('tasks.destroy');
});

require __DIR__.'/auth.php';
