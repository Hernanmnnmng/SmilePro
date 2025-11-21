@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold display-6 mb-4">Mijn Beschikbaarheid</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(auth()->user()->role === 'tandarts')
        @php
            $days = [
                'monday' => 'Maandag',
                'tuesday' => 'Dinsdag',
                'wednesday' => 'Woensdag',
                'thursday' => 'Donderdag',
                'friday' => 'Vrijdag',
                'saturday' => 'Zaterdag',
                'sunday' => 'Zondag',
            ];
        @endphp

        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body">
                <h4 class="fw-semibold mb-3">Nieuwe beschikbaarheid toevoegen</h4>
                <p class="text-muted mb-4">Kies een dag en geef de tijden op waarop je beschikbaar bent. We slaan per dag één blok op; verstuur het formulier opnieuw om de tijden bij te werken.</p>
                <form method="POST" action="{{ route('availability.store') }}" class="row g-3 align-items-end">
                    @csrf
                    <div class="col-md-4">
                        <label for="day_of_week" class="form-label">Dag</label>
                        <select id="day_of_week" name="day_of_week" class="form-select" required>
                            <option value="" disabled selected>Kies een dag</option>
                            @foreach($days as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="start_time" class="form-label">Starttijd</label>
                        <input type="time" id="start_time" name="start_time" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label for="end_time" class="form-label">Eindtijd</label>
                        <input type="time" id="end_time" name="end_time" class="form-control" required>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-success rounded-pill">Opslaan</button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-info">Alleen tandartsen kunnen hun beschikbaarheid instellen op deze pagina.</div>
    @endif

    <div class="mt-5">
        <h3 class="fw-semibold mb-2">Overzicht Beschikbaarheid</h3>
        @if($availabilities->isEmpty())
            <div class="alert alert-secondary mb-0">Nog geen beschikbaarheid vastgelegd.</div>
        @else
            <div class="list-group rounded-4 shadow-sm">
                @foreach($availabilities as $a)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $days[$a->day_of_week] ?? ucfirst($a->day_of_week) }}</strong>
                            <span class="text-muted ms-2">{{ $a->start_time }} - {{ $a->end_time }}</span>
                        </div>
                        <form method="POST" action="{{ route('availability.destroy', $a->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Verwijder deze beschikbaarheid?')">Verwijderen</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
