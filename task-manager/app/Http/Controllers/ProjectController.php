<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller {
    public function index() {
        return inertia('Projects/Index', [
            'projects' => Project::with('team')->get(),
            'teams' => Team::all(),
            'user' => Auth::user(), // Pass the authenticated user to the view
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'team_id' => 'required|exists:teams,id',
        ]);

        Project::create($request->all());

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }
    public function destroy($id)
{
    $project = \App\Models\Project::findOrFail($id);
    $project->delete();

    return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
}
}