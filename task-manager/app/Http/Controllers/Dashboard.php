<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Controller
{
  public function index()
{
    $teams = Team::with('owner')->where('user_id', Auth::id())->get();

    return Inertia::render('Dashboard', [
        'auth' => Auth::user(),
        'teams' => $teams,
    ]);
}
}
