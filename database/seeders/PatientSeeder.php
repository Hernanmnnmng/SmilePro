<?php

namespace Database\Seeders;

use App\Models\Patient; // Zorg ervoor dat dit is toegevoegd
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Patient::create([
            'name' => 'Jan Jansen',
            'birth_date' => '1980-05-15',
            'contact' => 'jan.jansen@example.com',
        ]);

        Patient::create([
            'name' => 'Marie de Vries',
            'birth_date' => '1992-11-27',
            'contact' => 'marie.vries@example.com',
        ]);
    }
}