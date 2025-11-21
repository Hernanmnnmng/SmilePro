@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Gebruiker Bewerken</h2>
    
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-800 p-2 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.user.update', $user->id) }}" class="bg-white p-6 rounded shadow">
        @csrf
        @method('PATCH')

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Naam</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-mail</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Nieuw Wachtwoord</label>
            <input type="password" name="password" id="password" 
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                   placeholder="Laat leeg om niet te wijzigen">
            <p class="text-sm text-gray-500 mt-1">Laat leeg om het huidige wachtwoord te behouden.</p>
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Bevestig Wachtwoord</label>
            <input type="password" name="password_confirmation" id="password_confirmation" 
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                   placeholder="Bevestig nieuw wachtwoord">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Rol</label>
            <div class="text-gray-600">{{ ucfirst($user->role) }}</div>
            <p class="text-sm text-gray-500 mt-1">Rol kan worden gewijzigd op de gebruikersbeheer pagina.</p>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Opslaan
            </button>
            <a href="{{ route('admin.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                Annuleren
            </a>
        </div>
    </form>
</div>
@endsection

