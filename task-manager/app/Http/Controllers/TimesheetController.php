<?php
namespace App\Http\Controllers;

use App\Models\Timesheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Inertia\Inertia;

class TimesheetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

public function index()
{
    $timesheets = Timesheet::where('user_id', Auth::id())->latest()->get();

    return Inertia::render('Timesheets/Index', [
        'timesheets' => $timesheets,
    ]);
}
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        $timesheet = Timesheet::create($validated);

        return response()->json(['message' => 'Timesheet submitted successfully!']);
    }

    public function update(Request $request, $id)
    {
        $timesheet = Timesheet::findOrFail($id);

        $validated = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        $timesheet->update($validated);

        return response()->json(['message' => 'Timesheet updated successfully!']);
    }
}