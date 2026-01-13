@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8 max-w-3xl">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ route('berichten.index') }}" class="text-blue-500 hover:text-blue-700 mr-4">
                <i class="bi bi-arrow-left"></i> Terug
            </a>
            <h2 class="text-2xl font-bold">Bericht Details</h2>
        </div>
        <div>
            <form action="{{ route('berichten.destroy', $bericht->id) }}" method="POST" class="inline" onsubmit="return confirm('Weet u zeker dat u dit bericht wilt verwijderen?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm">
                    <i class="bi bi-trash"></i> Verwijderen
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="border-b border-gray-200 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">{{ $bericht->subject }}</h3>
                    <p class="text-gray-600 mt-2">
                        @if(Auth::id() === $bericht->recipient_id)
                            <strong>Van:</strong> {{ $bericht->sender->name }}
                        @else
                            <strong>Naar:</strong> {{ $bericht->recipient->name }}
                        @endif
                    </p>
                </div>
                @if(!$bericht->is_read && Auth::id() === $bericht->recipient_id)
                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded font-medium">
                        Ongelezen
                    </span>
                @endif
            </div>
            <div class="mt-4 text-sm text-gray-500">
                <i class="bi bi-calendar"></i> {{ $bericht->created_at->format('d-m-Y H:i') }}
                @if($bericht->is_read && $bericht->read_at)
                    <br>
                    <i class="bi bi-check-circle"></i> Gelezen op {{ $bericht->read_at->format('d-m-Y H:i') }}
                @endif
            </div>
        </div>

        <!-- Body -->
        <div class="p-6 bg-gray-50">
            <div class="text-gray-800 whitespace-pre-wrap">
                {{ $bericht->body }}
            </div>
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-200 p-6 flex gap-4">
            <a href="{{ route('berichten.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                <i class="bi bi-arrow-left"></i> Terug naar overzicht
            </a>
            <a href="{{ route('berichten.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                <i class="bi bi-reply"></i> Nieuw bericht
            </a>
        </div>
    </div>
</div>

@if(Auth::id() === $bericht->recipient_id && !$bericht->is_read)
    <script>
        // Mark as read via AJAX
        fetch('{{ route('berichten.read', $bericht->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        });
    </script>
@endif
@endsection
