@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold mb-6">Gebruikersbeheer</h2>
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-2 rounded mb-4">{{ session('error') }}</div>
    @endif
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="py-2 px-2 sm:px-4 border-b text-left text-xs sm:text-sm">Naam</th>
                <th class="py-2 px-2 sm:px-4 border-b text-left text-xs sm:text-sm">E-mail</th>
                <th class="py-2 px-2 sm:px-4 border-b text-left text-xs sm:text-sm">Rol</th>
                <th class="py-2 px-2 sm:px-4 border-b text-left text-xs sm:text-sm">Beschikbaarheid</th>
                <th class="py-2 px-2 sm:px-4 border-b text-left text-xs sm:text-sm">Actie</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td class="py-2 px-2 sm:px-4 border-b text-sm">{{ $user->name }}</td>
                    <td class="py-2 px-2 sm:px-4 border-b text-sm break-all">{{ $user->email }}</td>
                    <td class="py-2 px-2 sm:px-4 border-b">
                        <form method="POST" action="{{ route('admin.user.role', $user->id) }}" class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                            @csrf
                            <select name="role" class="border rounded p-1 text-sm w-full sm:w-auto">
                                @foreach(['patient'=>'Patiënt','tandarts'=>'Tandarts','mondhygienist'=>'Mondhygiënist','assistent'=>'Assistent','management'=>'Management'] as $key=>$label)
                                    <option value="{{ $key }}" @if($user->role==$key) selected @endif>{{ $label }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded text-sm whitespace-nowrap">Opslaan</button>
                        </form>
                    </td>
                    <td class="py-2 px-2 sm:px-4 border-b">
                        <a href="{{ route('admin.user.availabilities', $user->id) }}" class="text-blue-600 underline text-sm">Bekijk</a>
                    </td>
                    <td class="py-2 px-2 sm:px-4 border-b">
                        <div class="flex flex-col sm:flex-row gap-2">
                            <a href="{{ route('admin.user.edit', $user->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm text-center whitespace-nowrap">
                                Bewerk
                            </a>
                            <form method="POST" action="{{ route('admin.user.destroy', $user->id) }}" class="inline" onsubmit="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm w-full sm:w-auto whitespace-nowrap">
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
@endsection
