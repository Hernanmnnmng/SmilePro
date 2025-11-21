<?php

namespace App\Http\Controllers;

use App\Models\AvailabilityNew;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityNewController extends Controller
{
    public function index()
    {
        $availabilities = AvailabilityNew::where('user_id', Auth::id())->get();
        return view('availability.index_new', compact('availabilities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);
        $data['user_id'] = Auth::id();
        AvailabilityNew::create($data);
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $availability = AvailabilityNew::where('user_id', Auth::id())->findOrFail($id);
        $availability->delete();
        return response()->json(['success' => true]);
    }

    public function all()
    {
        // For FullCalendar: return all availabilities for the logged-in user
        $availabilities = AvailabilityNew::where('user_id', Auth::id())->get()->map(function($a) {
            return [
                'id' => $a->id,
                'title' => 'Beschikbaar',
                'start' => $a->date . 'T' . $a->start_time,
                'end' => $a->date . 'T' . $a->end_time,
                'allDay' => false,
            ];
        });
        return response()->json($availabilities);
    }
}
