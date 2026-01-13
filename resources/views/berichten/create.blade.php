@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8 max-w-2xl">
    <div class="flex items-center mb-6">
        <a href="{{ route('berichten.index') }}" class="text-blue-500 hover:text-blue-700 mr-2">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="text-2xl font-bold">Nieuw Bericht</h2>
    </div>

    @if($errors->any())
        <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('berichten.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="recipient_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Ontvanger <span class="text-red-500">*</span>
                </label>
                <select id="recipient_id" name="recipient_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('recipient_id') border-red-500 @enderror" required>
                    <option value="">-- Selecteer een ontvanger --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('recipient_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} @if($user->role) ({{ ucfirst($user->role) }}) @endif
                        </option>
                    @endforeach
                </select>
                @error('recipient_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                    Onderwerp <span class="text-red-500">*</span>
                </label>
                <input type="text" id="subject" name="subject" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('subject') border-red-500 @enderror" 
                       placeholder="Voer het onderwerp in" value="{{ old('subject') }}" required maxlength="255">
                @error('subject')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="body" class="block text-sm font-medium text-gray-700 mb-2">
                    Bericht <span class="text-red-500">*</span>
                </label>
                <textarea id="body" name="body" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('body') border-red-500 @enderror" 
                          placeholder="Voer uw bericht in..." rows="8" required>{{ old('body') }}</textarea>
                @error('body')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-medium transition">
                    <i class="bi bi-send"></i> Verzenden
                </button>
                <a href="{{ route('berichten.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-medium transition">
                    Annuleren
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
