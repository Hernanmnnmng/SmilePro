<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AvailabilityNew;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;

/**
 * Controller for managing appointments
 * Handles booking, viewing, and managing appointments between patients and dental staff
 */
class AppointmentController extends Controller
{
    /**
     * Show the appointment booking form for a specific dentist/hygienist
     * Displays available time slots and existing appointments
     */
    public function create($tandarts_id)
    {
        try {
            $tandarts = User::findOrFail($tandarts_id);
            
            // Get future availabilities grouped by date
            $availabilities = AvailabilityNew::where('user_id', $tandarts_id)
                ->where('date', '>=', Carbon::today())
                ->orderBy('date')
                ->orderBy('start_time')
                ->get()
                ->groupBy('date');
            
            // Get existing appointments to show conflicts
            $existingAppointments = Appointment::where('tandarts_id', $tandarts_id)
                ->where('date', '>=', Carbon::today())
                ->whereIn('status', ['gepland', 'bevestigd'])
                ->get();
            
            return view('appointments.create', compact('tandarts', 'availabilities', 'existingAppointments'));
        } catch (ModelNotFoundException $e) {
            Log::error('Tandarts not found', ['tandarts_id' => $tandarts_id, 'error' => $e->getMessage()]);
            return redirect()->route('dashboard')->with('error', 'Tandarts niet gevonden.');
        } catch (\Exception $e) {
            Log::error('Error loading appointment creation form', [
                'tandarts_id' => $tandarts_id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('dashboard')->with('error', 'Er is een fout opgetreden bij het laden van de pagina.');
        }
    }

    /**
     * Create a new appointment
     * Validates availability and checks for conflicts before saving
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'tandarts_id' => 'required|exists:users,id',
                'date' => 'required|date|after_or_equal:today',
                'start_time' => 'required',
                'end_time' => 'required|after:start_time',
            ]);
            
            // Check if the worker is available at this time
            // The appointment must fall completely within an available slot
            $isAvailable = AvailabilityNew::where('user_id', $data['tandarts_id'])
                ->where('date', $data['date'])
                ->where(function($query) use ($data) {
                    $query->where(function($q) use ($data) {
                        // Appointment starts and ends within the slot
                        $q->where('start_time', '<=', $data['start_time'])
                          ->where('end_time', '>=', $data['end_time']);
                    });
                })
                ->exists();
            
            if (!$isAvailable) {
                return back()->withErrors(['time' => 'De geselecteerde tijd is niet beschikbaar.'])->withInput();
            }
            
            // Check for conflicts with existing appointments
            $hasConflict = Appointment::where('tandarts_id', $data['tandarts_id'])
                ->where('date', $data['date'])
                ->whereIn('status', ['gepland', 'bevestigd'])
                ->where(function($query) use ($data) {
                    $query->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                          ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                          ->orWhere(function($q) use ($data) {
                              $q->where('start_time', '<=', $data['start_time'])
                                ->where('end_time', '>=', $data['end_time']);
                          });
                })
                ->exists();
            
            if ($hasConflict) {
                return back()->withErrors(['time' => 'Er is al een afspraak op dit tijdstip.'])->withInput();
            }
            
            $data['patient_id'] = Auth::id();
            $data['status'] = 'gepland';
            Appointment::create($data);
            
            Log::info('Appointment created successfully', [
                'patient_id' => Auth::id(),
                'tandarts_id' => $data['tandarts_id'],
                'date' => $data['date']
            ]);
            
            return redirect()->route('dashboard')->with('success', 'Afspraak geboekt!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating appointment', [
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Er is een fout opgetreden bij het boeken van de afspraak. Probeer het opnieuw.')->withInput();
        }
    }

    /**
     * Show all appointments for the logged-in patient
     */
    public function myAppointments()
    {
        try {
            $appointments = Appointment::where('patient_id', Auth::id())
                ->with('tandarts')
                ->orderBy('date')
                ->get();
            
            return view('appointments.my', compact('appointments'));
        } catch (\Exception $e) {
            Log::error('Error loading patient appointments', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('dashboard')->with('error', 'Er is een fout opgetreden bij het laden van uw afspraken.');
        }
    }

    /**
     * Show the agenda/calendar for the logged-in dentist/hygienist
     * Displays all appointments with patient information
     */
    public function tandartsAgenda()
    {
        try {
            $appointments = Appointment::where('tandarts_id', Auth::id())
                ->with('patient')
                ->orderBy('date')
                ->get();
            
            // Prepare events for FullCalendar
            $calendarEvents = $appointments->map(function($a) {
                try {
                    return [
                        'title' => $a->patient->name . ' (' . ucfirst($a->status) . ')',
                        'start' => $a->date . 'T' . $a->start_time,
                        'end' => $a->date . 'T' . $a->end_time,
                        'backgroundColor' => $a->status === 'gepland' ? '#0d6efd' : ($a->status === 'bevestigd' ? '#198754' : '#dc3545'),
                    ];
                } catch (\Exception $e) {
                    Log::warning('Error mapping appointment to calendar event', [
                        'appointment_id' => $a->id,
                        'error' => $e->getMessage()
                    ]);
                    return null;
                }
            })->filter();
            
            return view('appointments.tandarts_calendar', compact('appointments', 'calendarEvents'));
        } catch (\Exception $e) {
            Log::error('Error loading tandarts agenda', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('dashboard')->with('error', 'Er is een fout opgetreden bij het laden van uw agenda.');
        }
    }
}
