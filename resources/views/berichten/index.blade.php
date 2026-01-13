@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Berichten Overzicht</h2>
        <a href="{{ route('berichten.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
            <i class="bi bi-plus-circle"></i> Nieuw Bericht
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4 flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button type="button" class="text-green-800 hover:text-green-900" onclick="this.parentElement.style.display='none';">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-4 rounded mb-4 flex justify-between items-center">
            <span>{{ session('error') }}</span>
            <button type="button" class="text-red-800 hover:text-red-900" onclick="this.parentElement.style.display='none';">&times;</button>
        </div>
    @endif

    <!-- Tabs -->
    <div class="mb-4 border-b border-gray-200">
        <nav class="flex space-x-8">
            <a href="{{ route('berichten.index') }}" class="py-2 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600">
                <i class="bi bi-inbox"></i> Ontvangen ({{ $berichten->count() }})
            </a>
            <a href="{{ route('berichten.sent') }}" class="py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                <i class="bi bi-send"></i> Verzonden
            </a>
        </nav>
    </div>

    @if($unreadCount > 0)
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
            <p class="text-sm text-blue-700">U hebt <strong>{{ $unreadCount }}</strong> ongelezen bericht(en)</p>
        </div>
    @endif

    @if($berichten->count() > 0)
        <div class="space-y-2">
            @foreach($berichten as $bericht)
                <div class="bg-white border border-gray-200 rounded hover:shadow-md transition p-4 cursor-pointer" 
                     onclick="window.location.href='{{ route('berichten.show', $bericht->id) }}'" 
                     style="background-color: {{ !$bericht->is_read ? '#f0f9ff' : 'white' }};">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                @if(!$bericht->is_read)
                                    <span class="inline-block w-2 h-2 bg-blue-500 rounded-full"></span>
                                @endif
                                <h3 class="text-lg font-semibold text-gray-800">{{ $bericht->subject }}</h3>
                            </div>
                            <p class="text-gray-600 text-sm mt-1">
                                <strong>Van:</strong> {{ $bericht->sender->name }}
                            </p>
                            <p class="text-gray-500 text-sm mt-1">{{ Str::limit($bericht->body, 100) }}</p>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <span class="text-xs text-gray-500">{{ $bericht->created_at->format('d-m-Y H:i') }}</span>
                            @if(!$bericht->is_read)
                                <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded font-medium">
                                    Ongelezen
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-gray-50 border border-gray-200 rounded p-8 text-center">
            <i class="bi bi-inbox text-4xl text-gray-400 mb-4" style="display: block;"></i>
            <p class="text-gray-600">U hebt geen berichten ontvangen.</p>
            <a href="{{ route('berichten.create') }}" class="text-blue-500 hover:text-blue-700 mt-2 inline-block">
                Stuur uw eerste bericht
            </a>
        </div>
    @endif
</div>
@endsection
