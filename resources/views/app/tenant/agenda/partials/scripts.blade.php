{{-- resources/views/app/tenant/agenda/partials/scripts.blade.php --}}

<!-- jQuery (if needed for your project) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Moment.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<!-- FullCalendar Core -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js'></script>
<!-- Full FullCalendar Scheduler Bundle (global version) -->
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/fullcalendar-scheduler@5.11.3/main.global.min.js"></script>



<style>
    #calendar {
        max-width: 1100px;
        margin: 0 auto;
    }

    .fc-event {
        cursor: pointer;
    }

    .fc-toolbar-title {
        font-size: 1.5em !important;
    }

    /* Resource styles */
    .fc-resource-cell {
        font-weight: bold;
    }

    .employee-resource {
        display: flex;
        align-items: center;
    }

    .employee-resource img {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        margin-right: 8px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        // Define employee resources
        var employeeResources = [{
                id: '1',
                title: 'Jan Janssen',
                eventColor: '#4CAF50',
                image: 'https://randomuser.me/api/portraits/men/1.jpg'
            },
            {
                id: '2',
                title: 'Emma Smits',
                eventColor: '#2196F3',
                image: 'https://randomuser.me/api/portraits/women/2.jpg'
            },
            {
                id: '3',
                title: 'Thomas de Vries',
                eventColor: '#FFC107',
                image: 'https://randomuser.me/api/portraits/men/3.jpg'
            },
            {
                id: '4',
                title: 'Sophie van Dijk',
                eventColor: '#9C27B0',
                image: 'https://randomuser.me/api/portraits/women/4.jpg'
            },
            {
                id: '5',
                title: 'Luuk Bakker',
                eventColor: '#F44336',
                image: 'https://randomuser.me/api/portraits/men/5.jpg'
            }
        ];

        // Example events with resources assigned
        var sampleEvents = [{
                title: 'Meeting with client',
                start: '2025-05-05T10:00:00',
                end: '2025-05-05T11:30:00',
                resourceId: '1'
            },
            {
                title: 'Building inspection',
                start: '2025-05-06T09:00:00',
                end: '2025-05-06T12:00:00',
                resourceId: '2'
            },
            {
                title: 'Document review',
                start: '2025-05-07T14:00:00',
                end: '2025-05-07T16:00:00',
                resourceId: '3'
            },
            {
                title: 'Training session',
                start: '2025-05-08',
                end: '2025-05-10',
                resourceId: '4',
                allDay: true
            },
            {
                title: 'Client consultation',
                start: '2025-05-06T15:00:00',
                end: '2025-05-06T16:30:00',
                resourceId: '5'
            }
        ];

        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek,dayGridDay'
            },

            initialView: 'dayGridMonth',
            selectable: true,
            selectMirror: true,
            dayMaxEvents: true,
            // weekNumbers: false,
            navLinks: true,
            editable: true,
            resources: employeeResources,
            events: sampleEvents,
            resourceLabelDidMount: function(info) {
                // Add employee image to resource label
                var employee = employeeResources.find(e => e.id === info.resource.id);
                if (employee && employee.image) {
                    var html = '<div class="employee-resource"><img src="' + employee.image +
                        '" alt="' + employee.title + '">' + employee.title + '</div>';
                    info.el.innerHTML = html;
                }
            },
            views: {
                resourceTimeGridDay: {
                    type: 'resourceTimeGrid',
                    buttonText: 'Day',
                    slotDuration: '00:30:00'
                },
                resourceTimeGridWeek: {
                    type: 'resourceTimeGrid',
                    buttonText: 'Week',
                    slotDuration: '01:00:00'
                }
            }
        });

        calendar.render();
    });
</script>
