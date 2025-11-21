@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold display-6 mb-4">Afspraak maken bij {{ $tandarts->name }}</h2>
    <form method="POST" action="{{ route('appointments.store') }}" class="mb-4">
        @csrf
        <input type="hidden" name="tandarts_id" value="{{ $tandarts->id }}">
        <div class="mb-3">
            <label for="date" class="form-label">Datum</label>
            <input type="date" name="date" id="date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="start_time" class="form-label">Starttijd</label>
            <input type="time" name="start_time" id="start_time" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="end_time" class="form-label">Eindtijd</label>
            <input type="time" name="end_time" id="end_time" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Afspraak boeken</button>
    </form>
    <h5>Beschikbare tijden:</h5>
    <ul>
        @foreach($availabilities as $a)
            <li>{{ ucfirst($a->day_of_week) }}: {{ $a->start_time }} - {{ $a->end_time }}</li>
        @endforeach
    </ul>
</div>
@endsection
