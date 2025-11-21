// FullCalendar JS integration for tandarts availability
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

window.initAvailabilityCalendar = function (calendarEl, events, onSelect) {
    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        initialView: 'timeGridWeek',
        selectable: true,
        editable: true,
        events: events,
        select: function(info) {
            if (onSelect) onSelect(info);
        },
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        height: 'auto',
    });
    calendar.render();
    return calendar;
};
