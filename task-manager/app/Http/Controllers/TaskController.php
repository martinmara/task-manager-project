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
            'users' => User::all(),
            'user' => \Illuminate\Support\Facades\Auth::user(), // Pass the user
        ]);

    }
    public function destroy($id)
{
    $task = \App\Models\Task::findOrFail($id);
    $task->delete();

    return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
}

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
            'due_date' => 'nullable|date',      // <-- Add due_date validation
            'comments' => 'nullable|string',   // <-- Add comments validation
        ]);

        

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'project_id' => $request->project_id,
            'user_id' => $request->user_id,
            'due_date' => $request->due_date,
            'comments' => $request->comments,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function complete($id)
    {
        $task = \App\Models\Task::findOrFail($id);
        $task->status = 'done';
        $task->save();

        return redirect()->route('tasks.index')->with('success', 'Task marked as completed.');
    }
}
