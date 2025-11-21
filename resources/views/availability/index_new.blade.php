@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold display-6 mb-4">Mijn Beschikbaarheid (Per Datum)</h2>
    <div id="availability-new-calendar"></div>
    <div class="mt-5">
        <h3 class="fw-semibold mb-2">Overzicht Beschikbaarheid</h3>
        <ul class="list-group" id="availability-list">
            @foreach($availabilities as $a)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $a->date }}: {{ $a->start_time }} - {{ $a->end_time }}
                    <button class="btn btn-danger btn-sm ms-2" onclick="deleteAvailability({{ $a->id }})">Verwijder</button>
                </li>
            @endforeach
        </ul>
    </div>
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.11/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.11/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.11/main.min.css" rel="stylesheet">
</div>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.11/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.11/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.11/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.11/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var Calendar = window.FullCalendar.Calendar;
    var calendarEl = document.getElementById('availability-new-calendar');
    if (!calendarEl || !Calendar) return;
    var calendar = new Calendar(calendarEl, {
        plugins: [window.FullCalendar.dayGridPlugin, window.FullCalendar.timeGridPlugin, window.FullCalendar.interactionPlugin],
        initialView: 'timeGridWeek',
        selectable: true,
        editable: false,
        events: '/availability-new/all',
        select: function(info) {
            var start = info.startStr;
            var end = info.endStr;
            fetch('/availability-new', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    date: start.substring(0,10),
                    start_time: start.substring(11,16),
                    end_time: end.substring(11,16)
                })
            }).then(function(r) { return r.json(); }).then(function() { calendar.refetchEvents(); location.reload(); });
        },
        eventClick: function(info) {
            if (confirm('Verwijder deze beschikbaarheid?')) {
                fetch('/availability-new/' + info.event.id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(function(r) { return r.json(); }).then(function() { calendar.refetchEvents(); location.reload(); });
            }
        },
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        height: 'auto',
    });
    calendar.render();
});
window.deleteAvailability = function(id) {
    if (confirm('Verwijder deze beschikbaarheid?')) {
        fetch('/availability-new/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(function(r) { return r.json(); }).then(function() { location.reload(); });
    }
}
</script>
<!-- Removed duplicate closing div -->
@endsection
