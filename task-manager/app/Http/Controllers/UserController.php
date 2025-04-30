<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return Inertia::render('Dashboard/Users/Index', ['users' => $users]);
        
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required|in:admin,employee',
            
        ]);

        $user->update($request->only(['name', 'email', 'role']));

        return redirect()->back()->with('message', 'User updated.');
    }
    public function updateRole(Request $request, User $user)
{
    $request->validate([
        'role_id' => 'required|exists:roles,id',
    ]);

    $user->roles()->sync([$request->role_id]);

    return back()->with('success', 'Role updated.');
}


    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('message', 'User deleted.');
    }
}
