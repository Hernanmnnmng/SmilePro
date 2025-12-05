<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PatientController extends Controller
{
     /**
     * Toon een overzicht van alle patiënten.
     */
    public function index()
    {
        try {
            $patients = Patient::orderBy('name')->get(); // gegevens ophalen
            return view('patients.index', compact('patients'));
        } catch (\Throwable $e) {
            Log::error('Patients index failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Kon patiënten niet laden.');
        }
    }

     /**
     * Toon het formulier om een nieuwe patiënt aan te maken.
     */

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'birth_date' => 'required|date',
                'contact' => 'nullable|string|max:255',
            ]);

            $patient = Patient::create($validated);
            return redirect()->route('patients.show', $patient)->with('success', 'Patiënt succesvol aangemaakt.');
        } catch (\Throwable $e) {
            Log::error('Failed to create patient', ['error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['general' => 'Er is een fout opgetreden bij het aanmaken van de patiënt.']);
        }
    }

    public function show(Patient $patient)
    {
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'birth_date' => 'required|date',
                'contact' => 'nullable|string|max:255',
            ]);

            $patient->update($validated);
            return redirect()->route('patients.show', $patient)->with('success', 'Patiënt succesvol bijgewerkt.');
        } catch (\Throwable $e) {
            Log::error('Failed to update patient', ['error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['general' => 'Er is een fout opgetreden bij het bijwerken van de patiënt.']);
        }
    }

    public function destroy(Patient $patient)
    {
        try {
            $patient->delete();
            return redirect()->route('patients.index')->with('success', 'Patiënt succesvol verwijderd.');
        } catch (\Throwable $e) {
            Log::error('Failed to delete patient', ['error' => $e->getMessage()]);
            return back()->withErrors(['general' => 'Er is een fout opgetreden bij het verwijderen van de patiënt.']);
        }
    }
}
