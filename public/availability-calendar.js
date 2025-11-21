// FullCalendar for per-date tandarts availability (vanilla JS, no module)
(function() {
    function loadScript(src, cb) {
        var s = document.createElement('script');
        s.src = src;
        s.onload = cb;
        document.head.appendChild(s);
    }
    function loadAll(cb) {
        loadScript('https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.11/index.global.min.js', function() {
            loadScript('https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.11/index.global.min.js', function() {
                loadScript('https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.11/index.global.min.js', function() {
                    loadScript('https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.11/index.global.min.js', cb);
                });
            });
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        loadAll(function() {
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
})();
