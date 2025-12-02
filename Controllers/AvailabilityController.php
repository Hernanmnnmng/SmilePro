<?php

namespace App\Http\Controllers;

use App\Models\AvailabilityNew;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Controller for managing worker availability
 * Shows availability management page for tandarts and mondhygienist
 */
class AvailabilityController extends Controller
{
    /**
     * Display the availability management page
     * Shows all availability slots for the logged-in worker
     */
    public function index()
    {
        try {
            $availabilities = AvailabilityNew::where('user_id', Auth::id())->get();
            return view('availability.index', compact('availabilities'));
        } catch (\Exception $e) {
            Log::error('Error loading availability index', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('dashboard')->with('error', 'Er is een fout opgetreden bij het laden van de beschikbaarheid.');
        }
    }
}
