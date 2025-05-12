<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;


class TeamController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard', [
            'users' => User::with('roles')->get(),
            'teams' => Team::with('members')->get(), 
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'members' => 'array',
            'members.*.name' => 'required|string',
            'members.*.email' => 'required|email',
            'members.*.role' => 'required|string',
        ]);

        $team = Team::create(['name' => $request->name]);

        foreach ($request->members as $member) {
            $team->members()->create($member);
        }

        return redirect()->route('teams.index');
    }

    public function update(Request $request, Team $team)
    {
        $team->update(['name' => $request->name]);

        $team->members()->delete(); // Clear old members
        foreach ($request->members as $member) {
            $team->members()->create($member);
        }

        return redirect()->route('teams.index');
    }
}
