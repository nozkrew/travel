import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import bootstrapPlugin from '@fullcalendar/bootstrap';
import frLocale from '@fullcalendar/core/locales/fr';

import '@fullcalendar/core/main.css';
import '@fullcalendar/daygrid/main.css';
import '@fullcalendar/timegrid/main.css';
import '@fullcalendar/list/main.css';

require('./jquery.collection');

$(".activites-collection").collection({
    add_at_the_end: true,
    add: '<a href="#" class="btn btn-primary mt-4"><i class="fas fa-plus-circle"></i></a>',
    allow_up: false,
    allow_down: false,
    init_with_n_elements: 1
});

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    var events = $(calendarEl).data('events');
    
    let calendar = new Calendar(calendarEl, {
        plugins: [ dayGridPlugin, timeGridPlugin, listPlugin, bootstrapPlugin],
        themeSystem: 'bootstrap',
        events: events,
        eventTextColor: '#FFFFFF',
        header: {
            left:   'prev,next, today',
            center: 'title',
            right:  'dayGridMonth, timeGridWeek, timeGridDay'
        },
        locale: frLocale
    });

    calendar.render();
});