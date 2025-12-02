@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Medewerker Overzicht</h2>
        <a href="{{ route('admin.index') }}" class="text-blue-600 hover:text-blue-800 underline text-sm">← Terug naar Gebruikersbeheer</a>
    </div>
    
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-2 rounded mb-4">{{ session('error') }}</div>
    @endif
    
    @if($medewerkers->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border">
                <thead>
                    <tr>
                        <th class="py-2 px-2 sm:px-4 border-b text-left text-xs sm:text-sm font-semibold">Naam</th>
                        <th class="py-2 px-2 sm:px-4 border-b text-left text-xs sm:text-sm font-semibold">E-mail</th>
                        <th class="py-2 px-2 sm:px-4 border-b text-left text-xs sm:text-sm font-semibold">Rol</th>
                        <th class="py-2 px-2 sm:px-4 border-b text-left text-xs sm:text-sm font-semibold">Beschikbaarheid</th>
                        <th class="py-2 px-2 sm:px-4 border-b text-left text-xs sm:text-sm font-semibold">Actie</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medewerkers as $medewerker)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-2 sm:px-4 border-b text-sm">{{ $medewerker->name }}</td>
                            <td class="py-2 px-2 sm:px-4 border-b text-sm break-all">{{ $medewerker->email }}</td>
                            <td class="py-2 px-2 sm:px-4 border-b">
                                @php
                                    $roleLabels = [
                                        'tandarts' => 'Tandarts',
                                        'mondhygienist' => 'Mondhygiënist',
                                        'assistent' => 'Assistent',
                                        'management' => 'Management'
                                    ];
                                @endphp
                                <span class="inline-block px-2 py-1 rounded text-xs font-medium
                                    @if($medewerker->role === 'tandarts') bg-blue-100 text-blue-800
                                    @elseif($medewerker->role === 'mondhygienist') bg-green-100 text-green-800
                                    @elseif($medewerker->role === 'assistent') bg-purple-100 text-purple-800
                                    @else bg-orange-100 text-orange-800
                                    @endif">
                                    {{ $roleLabels[$medewerker->role] ?? $medewerker->role }}
                                </span>
                            </td>
                            <td class="py-2 px-2 sm:px-4 border-b">
                                <a href="{{ route('admin.user.availabilities', $medewerker->id) }}" class="text-blue-600 hover:text-blue-800 underline text-sm">Bekijk</a>
                            </td>
                            <td class="py-2 px-2 sm:px-4 border-b">
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <a href="{{ route('admin.user.edit', $medewerker->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm text-center whitespace-nowrap">
                                        Bewerk
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 text-sm text-gray-600">
            <p>Totaal aantal medewerkers: <strong>{{ $medewerkers->count() }}</strong></p>
        </div>
    @else
        <div class="bg-gray-100 border border-gray-300 rounded p-6 text-center">
            <p class="text-gray-600">Er zijn nog geen medewerkers geregistreerd.</p>
        </div>
    @endif
</div>
@endsection




