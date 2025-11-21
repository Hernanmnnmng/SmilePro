<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Availability;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    // Show booking form for a tandarts
    public function create($tandarts_id)
    {
        $tandarts = User::findOrFail($tandarts_id);
        $availabilities = Availability::where('user_id', $tandarts_id)->get();
        return view('appointments.create', compact('tandarts', 'availabilities'));
    }

    // Store a new appointment
    public function store(Request $request)
    {
        $data = $request->validate([
            'tandarts_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);
        $data['patient_id'] = Auth::id();
        $data['status'] = 'gepland';
        Appointment::create($data);
        return redirect()->route('dashboard')->with('success', 'Afspraak geboekt!');
    }

    // Show patient's own appointments
    public function myAppointments()
    {
        $appointments = Appointment::where('patient_id', Auth::id())->with('tandarts')->orderBy('date')->get();
        return view('appointments.my', compact('appointments'));
    }

    // Show tandarts agenda
    public function tandartsAgenda()
    {
        $appointments = Appointment::where('tandarts_id', Auth::id())->with('patient')->orderBy('date')->get();
        // Prepare events for FullCalendar
        $calendarEvents = $appointments->map(function($a) {
            return [
                'title' => $a->patient->name . ' (' . ucfirst($a->status) . ')',
                'start' => $a->date . 'T' . $a->start_time,
                'end' => $a->date . 'T' . $a->end_time,
                'backgroundColor' => $a->status === 'gepland' ? '#0d6efd' : ($a->status === 'bevestigd' ? '#198754' : '#dc3545'),
            ];
        });
        return view('appointments.tandarts_calendar', compact('appointments', 'calendarEvents'));
    }
}
