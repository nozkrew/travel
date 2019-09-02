global.moment = require('moment');
import 'moment/locale/fr';
require('tempusdominus-bootstrap-4');

import 'tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css';

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import bootstrapPlugin from '@fullcalendar/bootstrap';
import interactionPlugin, { Draggable } from '@fullcalendar/interaction';
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
    init_with_n_elements: 1,
    after_init: function (){
        dateTimePickerInit();
    },
    after_add:function(){
        dateTimePickerInit();
    }
});

function dateTimePickerInit(){
    $('.datetimepicker').datetimepicker({
        format: 'DD/MM/YYYY H:mm',
        icons: {
           time: 'far fa-clock',
           //date: 'far fa-calendar-alt',
        },
        locale: 'fr',
        //defaultDate: "11/1/2013 12:00"
    });
}

function updateDate(info){
    
    var start = moment(info.event.start).format("DD/MM/YYYY HH:mm");
    
    var end = null;
    if(info.event.end !== null){
        end = moment(info.event.end).format("DD/MM/YYYY HH:mm");
    }
    
    $.ajax({
        url: info.event.extendedProps.urlUpdate,
        method: 'POST',
        data:{
            dateDeb: start,
            dateFin: end
        },
        success: function(response){
            if(response.erreur == true){
                $('#errorModal').find('.modal-body').html("<p>"+response.message+"</p>");
                $('#errorModal').modal('show');
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    //let draggableEl = document.getElementById('calendar');
    
    var events = $(calendarEl).data('events');
    var defaultDate = $(calendarEl).data('default-date');
    
    let calendar = new Calendar(calendarEl, {
        plugins: [ dayGridPlugin, timeGridPlugin, listPlugin, bootstrapPlugin, interactionPlugin ],
        themeSystem: 'bootstrap',
        events: events,
        eventTextColor: '#FFFFFF',
        header: {
            left:   'prev,next, today',
            center: 'title',
            right:  'dayGridMonth, timeGridWeek, timeGridDay'
        },
        locale: frLocale,
        editable: true,
        defaultDate: defaultDate,
        eventClick: function (info){
            
            var modal = $('#calendarModal');
            
            modal.find('.modal-title').text(info.event.title);
            modal.find('.modal-body').find("#eventStart").text(moment(info.event.start).format("DD/MM/YYYY HH:mm"));
            modal.find('.modal-body').find("#eventEnd").text(moment(info.event.end).format("DD/MM/YYYY HH:mm"));
            modal.find('.modal-body').find("#eventDescription").text(info.event.extendedProps.description);
            
            modal.find('.modal-footer').find('a').attr('href', info.event.extendedProps.urlDelete);
            $('#calendarModal').modal();

        },
        eventDrop: function(info) {
            //alert(info.event.title + " was dropped on " + info.event.start.toISOString());

            updateDate(info);

//            if (!confirm("Are you sure about this change?")) {
//              info.revert();
//            }
        },
        eventResize: function (info){
            //alert(info.event.title + " was resize on " + info.event.start.toISOString());
            updateDate(info);
        }
    });

    calendar.render();
    
});