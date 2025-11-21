<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    public function index()
    {
        $availabilities = Availability::where('user_id', Auth::id())->get();
        return view('availability.index', compact('availabilities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'day_of_week' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);
        $data['user_id'] = Auth::id();
        Availability::updateOrCreate(
            [
                'user_id' => $data['user_id'],
                'day_of_week' => $data['day_of_week'],
            ],
            [
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
            ]
        );
        return redirect()->route('availability.index')->with('success', 'Beschikbaarheid opgeslagen!');
    }

    public function destroy($id)
    {
        $availability = Availability::where('user_id', Auth::id())->findOrFail($id);
        $availability->delete();
        return redirect()->route('availability.index')->with('success', 'Beschikbaarheid verwijderd!');
    }
}
