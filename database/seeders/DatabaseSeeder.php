<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Controleer of de gebruiker al bestaat en update of maak deze aan
        User::updateOrCreate(
            ['email' => 'test@example.com'], // Unieke waarde
            [
                'name' => 'Test User',
                'password' => Hash::make('password'), // Voeg een standaard wachtwoord toe
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@example.com'], // Unieke waarde
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );
        $this->call(PatientSeeder::class);
    }
}