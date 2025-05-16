<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Inertia\Inertia;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use App\Models\User;
use App\Models\Team;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
    ]);
});

// Protected routes (auth + verified)
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard route
    Route::get('/dashboard', function () {
        $users = User::with('roles')->get();
        $teamsSummary = Team::all(); // Optional: pass to Dashboard.vue
        $teams = Team::with('members')->get();

        return Inertia::render('Dashboard', [
            'users' => $users,
            'teamsSummary' => $teamsSummary,
            'teams' => $teams,
            


        ]);
    })->name('dashboard');

    // Teams routes (Teams/Index.vue and related logic)
    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');
    Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');


    // Profile management routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
