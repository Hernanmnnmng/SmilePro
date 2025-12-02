@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Gebruikersbeheer</h2>
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-md transition duration-150 ease-in-out text-center">
                + Nieuwe Gebruiker
            </a>
            <a href="{{ route('admin.medewerkers') }}" class="text-gray-600 hover:text-gray-800 underline text-sm flex items-center">
                Medewerkers Overzicht →
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
    
    @if($users->count() > 0)
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
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 break-all">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form method="POST" action="{{ route('admin.user.role', $user->id) }}" class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                                        @csrf
                                        @php
                                            $roleLabels = [
                                                'patient' => 'Patiënt',
                                                'tandarts' => 'Tandarts',
                                                'mondhygienist' => 'Mondhygiënist',
                                                'assistent' => 'Assistent',
                                                'management' => 'Management'
                                            ];
                                            $roleColors = [
                                                'patient' => 'bg-gray-100 text-gray-800',
                                                'tandarts' => 'bg-blue-100 text-blue-800',
                                                'mondhygienist' => 'bg-green-100 text-green-800',
                                                'assistent' => 'bg-purple-100 text-purple-800',
                                                'management' => 'bg-orange-100 text-orange-800'
                                            ];
                                        @endphp
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $roleLabels[$user->role] ?? $user->role }}
                                            </span>
                                            <select name="role" class="border border-gray-300 rounded-md px-2 py-1 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                                @foreach($roleLabels as $key => $label)
                                                    <option value="{{ $key }}" @if($user->role == $key) selected @endif>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded-md text-xs transition duration-150 ease-in-out whitespace-nowrap">
                                                Wijzig
                                            </button>
                                        </div>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if(in_array($user->role, ['tandarts', 'mondhygienist', 'assistent']))
                                        <a href="{{ route('admin.user.availabilities', $user->id) }}" 
                                           class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors">
                                            Bekijk
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-sm">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.user.edit', $user->id) }}" 
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-md text-sm transition duration-150 ease-in-out">
                                            Bewerk
                                        </a>
                                        <form method="POST" 
                                              action="{{ route('admin.user.destroy', $user->id) }}" 
                                              class="inline"
                                              onsubmit="return confirm('Weet je zeker dat je {{ $user->name }} wilt verwijderen? Deze actie kan niet ongedaan worden gemaakt.');">
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
                <p>Totaal aantal gebruikers: <strong class="text-gray-900">{{ $users->count() }}</strong></p>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Geen gebruikers</h3>
            <p class="mt-1 text-sm text-gray-500">Er zijn nog geen gebruikers geregistreerd.</p>
            <div class="mt-6">
                <a href="{{ route('admin.users.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    + Nieuwe Gebruiker Toevoegen
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
