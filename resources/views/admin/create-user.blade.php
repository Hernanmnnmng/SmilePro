@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Nieuwe Gebruiker Aanmaken</h2>
            <a href="{{ route('admin.index') }}" class="text-gray-600 hover:text-gray-800 underline text-sm flex items-center gap-1">
                <span>←</span> Terug naar Overzicht
            </a>
        </div>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-6 sm:p-8">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Volledige Naam <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name') }}" 
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Bijv. Jan Jansen"
                        required
                        autofocus
                    >
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        E-mailadres <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}" 
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="bijv. jan.jansen@smilepro.nl"
                        required
                    >
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        Rol <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="role" 
                        id="role" 
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        required
                    >
                        <option value="">Selecteer een rol</option>
                        <option value="patient" {{ old('role') === 'patient' ? 'selected' : '' }}>Patiënt</option>
                        <option value="tandarts" {{ old('role') === 'tandarts' ? 'selected' : '' }}>Tandarts</option>
                        <option value="mondhygienist" {{ old('role') === 'mondhygienist' ? 'selected' : '' }}>Mondhygiënist</option>
                        <option value="assistent" {{ old('role') === 'assistent' ? 'selected' : '' }}>Assistent</option>
                        <option value="management" {{ old('role') === 'management' ? 'selected' : '' }}>Management</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Selecteer de rol van de nieuwe gebruiker.</p>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Wachtwoord <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Minimaal 8 karakters"
                        required
                        minlength="8"
                    >
                    <p class="mt-1 text-sm text-gray-500">Het wachtwoord moet minimaal 8 karakters lang zijn.</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Bevestig Wachtwoord <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation" 
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Herhaal het wachtwoord"
                        required
                        minlength="8"
                    >
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                    <button 
                        type="submit" 
                        class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-md transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Gebruiker Aanmaken
                    </button>
                    <a 
                        href="{{ route('admin.index') }}" 
                        class="flex-1 sm:flex-none bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium px-6 py-2.5 rounded-md text-center transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                    >
                        Annuleren
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

