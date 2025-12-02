@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold display-6 mb-4">Afspraak maken bij {{ $tandarts->name }}</h2>
    
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($availabilities->isEmpty())
        <div class="alert alert-warning">
            <p>Er zijn momenteel geen beschikbare tijden voor deze medewerker.</p>
        </div>
    @else
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body">
                <h4 class="fw-semibold mb-3">Beschikbare tijden</h4>
                <div class="row">
                    @foreach($availabilities as $date => $slots)
                        <div class="col-md-6 mb-3">
                            <div class="card border">
                                <div class="card-body">
                                    <h5 class="fw-bold">{{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</h5>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($slots as $slot)
                                            @php
                                                $isBooked = $existingAppointments->contains(function($apt) use ($date, $slot) {
                                                    return $apt->date == $date && 
                                                           (($apt->start_time <= $slot->start_time && $apt->end_time > $slot->start_time) ||
                                                            ($apt->start_time < $slot->end_time && $apt->end_time >= $slot->end_time) ||
                                                            ($apt->start_time >= $slot->start_time && $apt->end_time <= $slot->end_time));
                                                });
                                            @endphp
                                            @if(!$isBooked)
                                                <button type="button" 
                                                        class="btn btn-outline-success btn-sm slot-btn" 
                                                        data-date="{{ $date }}"
                                                        data-start="{{ $slot->start_time }}"
                                                        data-end="{{ $slot->end_time }}">
                                                    {{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }}
                                                </button>
                                            @else
                                                <button type="button" 
                                                        class="btn btn-outline-secondary btn-sm" 
                                                        disabled>
                                                    {{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }} (Bezet)
                                                </button>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">
                <h4 class="fw-semibold mb-3">Afspraak boeken</h4>
                <form method="POST" action="{{ route('appointments.store') }}">
                    @csrf
                    <input type="hidden" name="tandarts_id" value="{{ $tandarts->id }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="date" class="form-label">Datum</label>
                            <input type="date" name="date" id="date" class="form-control" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="start_time" class="form-label">Starttijd</label>
                            <input type="time" name="start_time" id="start_time" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="end_time" class="form-label">Eindtijd</label>
                            <input type="time" name="end_time" id="end_time" class="form-control" required>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Afspraak boeken</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.slot-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('date').value = this.dataset.date;
            document.getElementById('start_time').value = this.dataset.start;
            document.getElementById('end_time').value = this.dataset.end;
            
            // Scroll to form
            document.querySelector('form').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });
    });
});
</script>
@endsection
