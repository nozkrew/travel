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
var moment = require('moment');

require('eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js');
require('eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css');

$(".activites-collection").collection({
    add_at_the_end: true,
    add: '<a href="#" class="btn btn-primary mt-4"><i class="fas fa-plus-circle"></i></a>',
    allow_up: false,
    allow_down: false,
    init_with_n_elements: 1,
    after_init: function (){
        dateTimePickerInit();
    },
    after_add:function(){
        dateTimePickerInit();
    }
});


function dateTimePickerInit(){
    //A revoir
    $('.datetimepicker').datetimepicker({
        format: 'DD/MM/YYYY H:mm'
    });
}


document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    var events = $(calendarEl).data('events');
    var defaultDate = $(calendarEl).data('default-date');
    
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
        locale: frLocale,
        defaultDate: defaultDate,
        eventClick: function (info){
            
            var modal = $('#calendarModal');
            
            modal.find('.modal-title').text(info.event.title);
            modal.find('.modal-body').find("#eventStart").text(moment(info.event.start).format("DD/MM/YYYY HH:mm"));
            modal.find('.modal-body').find("#eventEnd").text(moment(info.event.end).format("DD/MM/YYYY HH:mm"));
            modal.find('.modal-body').find("#eventDescription").text(info.event.extendedProps.description);
            
            modal.find('.modal-footer').find('a').attr('href', info.event.extendedProps.urlDelete);
//            $('#modalBody').html(event.description);
//            $('#eventUrl').attr('href',event.url);
            $('#calendarModal').modal();
            
//            alert('Event: ' + info.event.title);
//            alert('Coordinates: ' + info.jsEvent.pageX + ',' + info.jsEvent.pageY);
//            alert('View: ' + info.view.type);
        }
    });

    calendar.render();
});