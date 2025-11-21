@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold display-6 mb-4">Agenda (Kalenderweergave)</h2>
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.11/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.11/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.11/main.min.css" rel="stylesheet">
    <div id="agenda-calendar"></div>
    <script>
        window.tandartsAppointments = @json($calendarEvents);
    </script>
    <script type="module">
        import { Calendar } from 'https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.11/index.global.min.js';
        import dayGridPlugin from 'https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.11/index.global.min.js';
        import timeGridPlugin from 'https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.11/index.global.min.js';
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('agenda-calendar');
            const calendar = new Calendar(calendarEl, {
                plugins: [dayGridPlugin, timeGridPlugin],
                initialView: 'timeGridWeek',
                locale: 'nl',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: window.tandartsAppointments,
            });
            calendar.render();
        });
    </script>
</div>
@endsection
