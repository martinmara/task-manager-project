<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Project;
use App\Models\Task;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
  public function index()
{
    $teams = Team::with('owner')->where('user_id', Auth::id())->get();

    return Inertia::render('Dashboard', [
        'auth' => Auth::user(),
        'projects' => Project::with('tasks')->get(),
        'tasks' => Task::with('project')->latest()->take(5)->get(),
        'teams' => Team::with('users')->get(),
        'user' => Auth::user()
    ]);
}
}
