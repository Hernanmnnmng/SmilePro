@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Beschikbaarheid van {{ $user->name }}</h2>
    <ul>
        @forelse($availabilities as $a)
            <li>{{ ucfirst($a->day_of_week) }}: {{ $a->start_time }} - {{ $a->end_time }}</li>
        @empty
            <li>Geen beschikbaarheid ingesteld.</li>
        @endforelse
    </ul>
    <a href="{{ route('admin.index') }}" class="mt-4 inline-block bg-gray-500 text-white px-4 py-2 rounded">Terug</a>
</div>
@endsection
