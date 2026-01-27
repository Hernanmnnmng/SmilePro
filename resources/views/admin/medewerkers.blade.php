@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h2 class="text-2xl font-bold text-white">Medewerker Overzicht</h2>
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('admin.medewerkers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-md transition duration-150 ease-in-out text-center">
                + Nieuwe Medewerker
            </a>
            <a href="{{ route('admin.index') }}" class="text-white hover:text-gray-800 underline text-sm flex items-center">
                ← Terug naar Gebruikersbeheer
            </a>
        </div>
    </div>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    
    @if($medewerkers->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Naam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">E-mail</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Beschikbaarheid</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acties</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($medewerkers as $medewerker)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $medewerker->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 break-all">{{ $medewerker->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $roleLabels = [
                                            'tandarts' => 'Tandarts',
                                            'mondhygienist' => 'Mondhygiënist',
                                            'assistent' => 'Assistent',
                                            'management' => 'Management'
                                        ];
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($medewerker->role === 'tandarts') bg-blue-100 text-blue-800
                                        @elseif($medewerker->role === 'mondhygienist') bg-green-100 text-green-800
                                        @elseif($medewerker->role === 'assistent') bg-purple-100 text-purple-800
                                        @else bg-orange-100 text-orange-800
                                        @endif">
                                        {{ $roleLabels[$medewerker->role] ?? $medewerker->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.user.availabilities', $medewerker->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors">
                                        Bekijk
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.user.edit', $medewerker->id) }}" 
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-md text-sm transition duration-150 ease-in-out">
                                            Bewerk
                                        </a>
                                        <form method="POST" 
                                              action="{{ route('admin.user.destroy', $medewerker->id) }}" 
                                              class="inline"
                                              onsubmit="return confirm('Weet je zeker dat je {{ $medewerker->name }} wilt verwijderen? Deze actie kan niet ongedaan worden gemaakt.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-md text-sm transition duration-150 ease-in-out">
                                                Verwijder
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-600">
                <p class="text-white">Totaal aantal medewerkers: <strong class="text-white">{{ $medewerkers->count() }}</strong></p>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Geen medewerkers</h3>
            <p class="mt-1 text-sm text-gray-500">Er zijn nog geen medewerkers geregistreerd.</p>
            <div class="mt-6">
                <a href="{{ route('admin.medewerkers.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    + Nieuwe Medewerker Toevoegen
                </a>
            </div>
        </div>
    @endif
</div>
@endsection




