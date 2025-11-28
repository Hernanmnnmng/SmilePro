<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
     /**
     * Toon een overzicht van alle patiënten.
     */
    public function index()
    {
        // Haal alle patiënten op uit de database
        $patients = Patient::all();

        // Stuur de patiënten naar de view
        return view('patients.index', compact('patients'));
    }
}
