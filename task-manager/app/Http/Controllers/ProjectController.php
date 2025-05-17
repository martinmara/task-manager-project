<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Team;
use Illuminate\Http\Request;

class ProjectController extends Controller {
    public function index() {
        return inertia('Projects/Index', [
            'projects' => Project::with('team')->get(),
            'teams' => Team::all()
            
        ]);
    }

    public function store(Request $request) {
        $projects = Project::with('users')->get();

        $request->validate([
            'name' => 'required|string',
            'team_id' => 'required|exists:teams,id',
        ]);

        Project::create($request->all());

        
    }
    
}
