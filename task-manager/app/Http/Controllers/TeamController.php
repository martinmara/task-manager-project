<?php
namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    use AuthorizesRequests;

    public function index()
{
    $teams = Team::with('members')->get();
    $users = User::all();

    return Inertia::render('Teams/Index', [
        'teams' => $teams,
        'users' => $users
    ]);
}
public function destroy(Team $team)
{
    $team->members()->detach(); // Detach members first
    $team->delete();

    return redirect()->route('teams.index')->with('success', 'Team deleted.');
}
public function edit(Team $team)
{
    $this->authorize('update', $team); // requires policies if needed

    $users = User::all();

    return Inertia::render('Teams/Edit', [
        'team' => $team,
        'users' => $users
    ]);
}
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'member_ids' => 'nullable|array',
        'member_ids.*' => 'exists:users,id',
    ]);

    $team = Team::create([
        'user_id' => Auth::id(),
        'name' => $request->name,
        'description' => $request->description,
    ]);

    if ($request->filled('member_ids')) {
        $team->members()->attach($request->member_ids);
    }

    return redirect()->route('teams.index');
}

    public function update(Request $request, Team $team)
    {
        $this->authorize('update', $team); // requires policies if needed

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $team->update($request->only(['name', 'description']));

        return redirect()->back();
    }
}
