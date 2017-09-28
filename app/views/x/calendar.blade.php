@extends('edoc.ace') 
@section('head_meta')
<title>ฝ่ายพัฒนาทรัพยากรบุคคล</title> 
{{ HTML::style(asset('css/autocomplete.css'))}}
{{ HTML::style(asset('theme/ace/assets/css/fullcalendar.css'))}}
<style>
    .fc-event-title::before{
    }
</style>
@stop
<?php
?>
@section('breadcrumbs')
<li class="active hidden-phone">Calendar</li>
@stop
@section('body')
<div class="page-header position-relative">
    <h1>
        e-Doc Calendar
        <small>
            <i class="icon-double-angle-right"></i>            
        </small>
    </h1>
</div><!--/.page-header-->

<div class="row-fluid">
    <div class="span12">
        <!--PAGE CONTENT BEGINS-->

        <div class="row-fluid">
            <div class="span12">
                <div class="space"></div>

                <div id="calendar"></div>
            </div>
        </div>

        <!--PAGE CONTENT ENDS-->
    </div><!--/.span-->
</div><!--/.row-fluid-->
@stop
@section('foot')
<script type="text/javascript">
    $(function () {

        /* initialize the external events
         -----------------------------------------------------------------*/

        $('#external-events div.external-event').each(function () {

            // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
            // it doesn't need to have a start or end
            var eventObject = {
                title: $.trim($(this).text()) // use the element's text as the event title
            };

            // store the Event Object in the DOM element so we can get to it later
            $(this).data('eventObject', eventObject);

            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 999,
                revert: true, // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });

        });




        /* initialize the calendar
         -----------------------------------------------------------------*/

        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();


        var calendar = $('#calendar').fullCalendar({
            buttonText: {
                prev: '<i class="icon-chevron-left"></i>',
                next: '<i class="icon-chevron-right"></i>'
            },
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: 'loadcalendar',
            eventLimit: true,
            lang: 'th',
            editable: false,
            droppable: false, // this allows things to be dropped onto the calendar !!!
            drop: function (date, allDay) { // this function is called when something is dropped

                // retrieve the dropped element's stored Event Object
                var originalEventObject = $(this).data('eventObject');
                var $extraEventClass = $(this).attr('data-class');


                // we need to copy it, so that multiple events don't have a reference to the same object
                var copiedEventObject = $.extend({}, originalEventObject);

                // assign it the date that was reported
                copiedEventObject.start = date;
                copiedEventObject.allDay = allDay;
                if ($extraEventClass)
                    copiedEventObject['className'] = [$extraEventClass];

                // render the event on the calendar
                // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

                // is the "remove after drop" checkbox checked?
                if ($('#drop-remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    $(this).remove();
                }

            }
            ,
            selectable: false,
            selectHelper: true,
            select: function (start, end, allDay) {
                bootbox.prompt("New Event Title:", function (title) {
                    if (title !== null) {
                        calendar.fullCalendar('renderEvent',
                                {
                                    title: title,
                                    start: start,
                                    end: end,
                                    allDay: allDay
                                },
                        true // make the event "stick"
                                );
                    }
                });


                calendar.fullCalendar('unselect');
            },
            eventRender: function (event, element, calEvent) {
                element.find(".fc-event-title").before($("<span class=\"fc-event-icons\"></span>").html(event.img));
            }, dayClick: function (date, allDay, jsEvent, view) {
                return fault;

                if (allDay) {
                    // Clicked on the entire day
                    $('#calendar')
                            .fullCalendar('changeView', 'agendaWeek'/* or 'basicDay' */)
                            .fullCalendar('gotoDate',
                                    date.getFullYear(), date.getMonth(), date.getDate());
                }
            }

        });


    })
</script>
@stop
@section('foot_meta')
{{ HTML::script(asset('js/newpost.js'))}}
{{ HTML::script(asset('theme/ace/assets/js/fullcalendar.min.js'))}}
{{ HTML::script(asset('js/fullcalendar/lang/th.js'))}}
{{ HTML::script(asset('theme/ace/assets/js/bootbox.min.js'))}}
@stop
@section('page_title')Calendar @stop

