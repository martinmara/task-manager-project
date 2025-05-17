<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller {
    public function index() {
    return inertia('Tasks/Index', [
        'tasks' => Task::with(['project.team.users', 'user'])->get(),
        'projects' => Project::with('team.users')->get(),
        'users' => User::all()
    ]);
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
        ]);


        Task::create($request->all());
    }
}
