<?php

namespace App\Http\Controllers;

use App\Models\AvailabilityNew;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Controller for managing date-based availability slots
 * Handles creating, viewing, and deleting availability entries
 */
class AvailabilityNewController extends Controller
{
    /**
     * Show the availability calendar view (legacy)
     */
    public function index()
    {
        try {
            $availabilities = AvailabilityNew::where('user_id', Auth::id())->get();
            return view('availability.index_new', compact('availabilities'));
        } catch (\Exception $e) {
            Log::error('Error loading availability index', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('dashboard')->with('error', 'Er is een fout opgetreden bij het laden van de beschikbaarheid.');
        }
    }

    /**
     * Create a new availability slot
     * Accepts both form submissions and JSON requests
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'date' => 'required|date|after_or_equal:today',
                'start_time' => 'required',
                'end_time' => 'required|after:start_time',
            ]);
            
            $data['user_id'] = Auth::id();
            AvailabilityNew::create($data);
            
            Log::info('Availability created successfully', [
                'user_id' => Auth::id(),
                'date' => $data['date']
            ]);
            
            if ($request->expectsJson()) {
                return response()->json(['success' => true]);
            }
            
            return redirect()->route('availability.index')->with('success', 'Beschikbaarheid opgeslagen!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'errors' => $e->errors()], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating availability', [
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Er is een fout opgetreden.'], 500);
            }
            
            return back()->with('error', 'Er is een fout opgetreden bij het opslaan van de beschikbaarheid.')->withInput();
        }
    }

    /**
     * Delete an availability slot
     * Accepts both form submissions and JSON requests
     */
    public function destroy($id)
    {
        try {
            $availability = AvailabilityNew::where('user_id', Auth::id())->findOrFail($id);
            $availability->delete();
            
            Log::info('Availability deleted successfully', [
                'user_id' => Auth::id(),
                'availability_id' => $id
            ]);
            
            if (request()->expectsJson()) {
                return response()->json(['success' => true]);
            }
            
            return redirect()->route('availability.index')->with('success', 'Beschikbaarheid verwijderd!');
        } catch (ModelNotFoundException $e) {
            Log::warning('Availability not found for deletion', [
                'user_id' => Auth::id(),
                'availability_id' => $id
            ]);
            
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Beschikbaarheid niet gevonden.'], 404);
            }
            
            return redirect()->route('availability.index')->with('error', 'Beschikbaarheid niet gevonden.');
        } catch (\Exception $e) {
            Log::error('Error deleting availability', [
                'user_id' => Auth::id(),
                'availability_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Er is een fout opgetreden.'], 500);
            }
            
            return redirect()->route('availability.index')->with('error', 'Er is een fout opgetreden bij het verwijderen van de beschikbaarheid.');
        }
    }

    /**
     * Get all availability slots as JSON for calendar display
     * Used by FullCalendar or other calendar components
     */
    public function all()
    {
        try {
            $availabilities = AvailabilityNew::where('user_id', Auth::id())
                ->get()
                ->map(function($a) {
                    try {
                        return [
                            'id' => $a->id,
                            'title' => 'Beschikbaar',
                            'start' => $a->date . 'T' . $a->start_time,
                            'end' => $a->date . 'T' . $a->end_time,
                            'allDay' => false,
                        ];
                    } catch (\Exception $e) {
                        Log::warning('Error mapping availability to calendar event', [
                            'availability_id' => $a->id ?? null,
                            'error' => $e->getMessage()
                        ]);
                        return null;
                    }
                })
                ->filter();
            
            return response()->json($availabilities);
        } catch (\Exception $e) {
            Log::error('Error loading all availabilities', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Er is een fout opgetreden bij het laden van de beschikbaarheid.'], 500);
        }
    }
}
