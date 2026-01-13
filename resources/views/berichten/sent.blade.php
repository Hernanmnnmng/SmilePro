@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Verzonden Berichten</h2>
        <a href="{{ route('berichten.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
            <i class="bi bi-plus-circle"></i> Nieuw Bericht
        </a>
    </div>

    <!-- Tabs -->
    <div class="mb-4 border-b border-gray-200">
        <nav class="flex space-x-8">
            <a href="{{ route('berichten.index') }}" class="py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                <i class="bi bi-inbox"></i> Ontvangen
            </a>
            <a href="{{ route('berichten.sent') }}" class="py-2 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600">
                <i class="bi bi-send"></i> Verzonden
            </a>
        </nav>
    </div>

    @if($berichten->count() > 0)
        <div class="space-y-2">
            @foreach($berichten as $bericht)
                <div class="bg-white border border-gray-200 rounded hover:shadow-md transition p-4 cursor-pointer" 
                     onclick="window.location.href='{{ route('berichten.show', $bericht->id) }}'">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $bericht->subject }}</h3>
                            <p class="text-gray-600 text-sm mt-1">
                                <strong>Naar:</strong> {{ $bericht->recipient->name }}
                            </p>
                            <p class="text-gray-500 text-sm mt-1">{{ Str::limit($bericht->body, 100) }}</p>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <span class="text-xs text-gray-500">{{ $bericht->created_at->format('d-m-Y H:i') }}</span>
                            @if($bericht->is_read)
                                <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs rounded font-medium">
                                    Gelezen
                                </span>
                            @else
                                <span class="inline-block px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded font-medium">
                                    Niet gelezen
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-gray-50 border border-gray-200 rounded p-8 text-center">
            <i class="bi bi-send text-4xl text-gray-400 mb-4" style="display: block;"></i>
            <p class="text-gray-600">U hebt nog geen berichten verzonden.</p>
            <a href="{{ route('berichten.create') }}" class="text-blue-500 hover:text-blue-700 mt-2 inline-block">
                Stuur uw eerste bericht
            </a>
        </div>
    @endif
</div>
@endsection
