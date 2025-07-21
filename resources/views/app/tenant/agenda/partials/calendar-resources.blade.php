{{-- resources/views/app/tenant/agenda/partials/calendar-resources.blade.php --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if FullCalendar is available
        if (typeof FullCalendar === 'undefined') {
            console.error('FullCalendar library not loaded!');
            return;
        }

        // Initialize employee and events data
        let employees = [];
        let events = [];

        // Fetch employees first
        fetchEmployees().then(() => {
            // Initialize calendar after we have employee data
            initializeCalendar();
            // Set up draggable events
            setupDraggableEvents();
        }).catch(error => {
            console.error('Error initializing calendar:', error);
            // Initialize calendar anyway, but without resources
            initializeCalendar();
        });

        // Fetch employees data
        function fetchEmployees() {
            return new Promise((resolve, reject) => {
                fetch('/app/tenant/ajax/getEmployes')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Fetched employees:', data);

                        // Check if employees data is valid
                        if (data && Array.isArray(data) && data.length > 0) {
                            employees = data;
                            resolve(employees);
                        } else {
                            console.warn('Employees data is empty or invalid:', data);
                            reject('No valid employee data');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching employees:', error);
                        reject(error);
                    });
            });
        }

        // Function to generate resources from employees
        function generateResourcesFromEmployees() {
            return employees.map(employee => {
                return {
                    id: employee.id.toString(),
                    title: employee.name + ' ' + (employee.surname || ''),
                    eventColor: getRandomColor(employee.id)
                };
            });
        }

        function getRandomColor(seed) {
            // Generate a pseudo-random color based on the employee ID
            const colors = [
                '#4285F4', '#EA4335', '#FBBC05', '#34A853',
                '#3498db', '#e74c3c', '#2ecc71', '#f39c12',
                '#9b59b6', '#1abc9c', '#d35400', '#27ae60'
            ];
            return colors[(seed || 0) % colors.length];
        }

        function initializeCalendar() {
            // Get calendar element
            const calendarEl = document.getElementById('calendar');
            if (!calendarEl) {
                console.error("Calendar element not found!");
                return;
            }

            // Generate resources from employees
            const resources = generateResourcesFromEmployees();

            // Initialize the calendar with resource view
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'resourceTimeGridDay', // Default to resource day view
                schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source', // For non-commercial use

                // Define available views
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,resourceTimeGridDay'
                },

                // Basic settings
                locale: 'nl',
                editable: true,
                droppable: true,
                selectable: true,
                navLinks: true,
                nowIndicator: true,
                dayMaxEvents: true,
                slotMinTime: '07:00',
                slotMaxTime: '22:00',

                // Resource-specific settings
                resources: resources,

                // Group by resource (employee) in day view
                resourceAreaHeaderContent: 'Employees',
                resourceAreaWidth: '15%',
                resourceLabelDidMount: function(info) {
                    // Add employee avatar to resource label
                    const employee = employees.find(e => e.id.toString() === info.resource.id);
                    if (employee) {
                        const avatar = document.createElement('img');
                        avatar.src =
                            `https://ui-avatars.com/api/?name=${encodeURIComponent(employee.name)}&background=${info.resource.eventColor.substring(1)}&color=fff`;
                        avatar.style.width = '24px';
                        avatar.style.height = '24px';
                        avatar.style.borderRadius = '50%';
                        avatar.style.marginRight = '8px';
                        avatar.style.verticalAlign = 'middle';

                        info.el.prepend(avatar);
                    }
                },

                // Business hours
                businessHours: {
                    daysOfWeek: [1, 2, 3, 4, 5], // Monday - Friday
                    startTime: '08:00',
                    endTime: '18:00',
                },

                // Event handlers
                select: function(info) {
                    // Open the event creation modal with selected date
                    var startDateTime = info.start;
                    var endDateTime = info.end;
                    var resourceId = info.resource ? info.resource.id : null;

                    // Format the dates for the modal
                    var formattedStart = moment(startDateTime).format('YYYY-MM-DD HH:mm');
                    var formattedEnd = moment(endDateTime).format('YYYY-MM-DD HH:mm');

                    // Set values in the create event modal
                    var startDateInput = document.getElementById('event_start_date');
                    var endDateInput = document.getElementById('event_end_date');
                    var employeeSelect = document.getElementById('employee_id');

                    if (startDateInput && endDateInput) {
                        startDateInput.value = formattedStart;
                        endDateInput.value = formattedEnd;

                        // Set employee if applicable
                        if (employeeSelect && resourceId) {
                            employeeSelect.value = resourceId;

                            // Trigger change event if using select2
                            if ($.fn.select2 && $(employeeSelect).data('select2')) {
                                $(employeeSelect).trigger('change');
                            }
                        }

                        // Open the modal if it exists
                        var createModalEl = document.getElementById('createEventModal');
                        if (createModalEl && typeof bootstrap !== 'undefined') {
                            var createModal = new bootstrap.Modal(createModalEl);
                            createModal.show();
                        }
                    }
                },

                eventClick: function(info) {
                    // Handle event click - show event details
                    var eventId = info.event.id;

                    // Get the event detail modal
                    var detailModalEl = document.getElementById('eventDetailModal');

                    if (detailModalEl && typeof bootstrap !== 'undefined') {
                        var detailModal = new bootstrap.Modal(detailModalEl);

                        // Get detail elements
                        var titleEl = document.getElementById('event-detail-title');
                        var timeEl = document.getElementById('event-detail-time');

                        if (titleEl) {
                            titleEl.textContent = info.event.title;
                        }

                        if (timeEl) {
                            // Include timing information
                            let timeText = moment(info.event.start).format('DD MMM YYYY, HH:mm') +
                                ' - ' +
                                moment(info.event.end || info.event.start).format('HH:mm');

                            // Add employee info
                            const resourceId = info.event.getResources()[0]?.id;
                            if (resourceId) {
                                const employee = employees.find(e => e.id.toString() ===
                                resourceId);
                                if (employee) {
                                    timeText += ' | ' + employee.name + ' ' + (employee.surname ||
                                        '');
                                }
                            }

                            timeEl.textContent = timeText;
                        }

                        detailModal.show();
                    }
                },

                eventDrop: function(info) {
                    // Handle event drag & drop
                    const eventData = {
                        id: info.event.id,
                        start: moment(info.event.start).format('YYYY-MM-DD HH:mm:ss'),
                        end: moment(info.event.end || info.event.start).format(
                            'YYYY-MM-DD HH:mm:ss')
                    };

                    // Add employee_id from resource if it exists
                    const resources = info.event.getResources();
                    if (resources.length > 0) {
                        eventData.employee_id = resources[0].id;
                    }

                    // Show a temporary toast
                    showToast('Updating event...', 'info');

                    // Update the event
                    updateEventDataAsync(eventData)
                        .then(success => {
                            if (!success) {
                                // If failed, revert the drag
                                info.revert();
                                showToast('Failed to update event', 'error');
                            } else {
                                showToast('Event updated successfully', 'success');
                            }
                        })
                        .catch(error => {
                            console.error('Error updating event:', error);
                            info.revert();
                            showToast('An error occurred', 'error');
                        });
                },

                eventResize: function(info) {
                    // Handle event resize
                    const eventData = {
                        id: info.event.id,
                        start: moment(info.event.start).format('YYYY-MM-DD HH:mm:ss'),
                        end: moment(info.event.end || info.event.start).format(
                            'YYYY-MM-DD HH:mm:ss')
                    };

                    // Show a temporary toast
                    showToast('Updating event duration...', 'info');

                    // Update the event
                    updateEventDataAsync(eventData)
                        .then(success => {
                            if (!success) {
                                info.revert();
                                showToast('Failed to update event duration', 'error');
                            } else {
                                showToast('Event duration updated successfully', 'success');
                            }
                        })
                        .catch(error => {
                            console.error('Error updating event:', error);
                            info.revert();
                            showToast('An error occurred', 'error');
                        });
                },

                eventReceive: function(info) {
                    // Triggered when an external event is dropped on the calendar
                    const eventData = {
                        title: info.event.title,
                        start: moment(info.event.start).format('YYYY-MM-DD HH:mm:ss'),
                        end: moment(info.event.end || moment(info.event.start).add(1, 'hour'))
                            .format('YYYY-MM-DD HH:mm:ss'),
                        id: info.event.id || null
                    };

                    // Add employee_id from resource if it exists
                    const resources = info.event.getResources();
                    if (resources.length > 0) {
                        eventData.employee_id = resources[0].id;
                    }

                    // If this is an inspection being dropped, handle assignment
                    if (info.event.extendedProps?.type === 'inspection') {
                        const inspectionId = info.event.id;
                        const employeeId = eventData.employee_id;

                        if (inspectionId && employeeId) {
                            assignInspectionToEmployee(inspectionId, employeeId);
                        }
                    }
                },

                // Data source for events
                events: function(info, successCallback, failureCallback) {
                    fetch('/app/tenant/ajax/getEvents')
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Failed to fetch events');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Store all events globally
                            events = data;

                            // Make sure events is an array
                            if (!Array.isArray(data)) {
                                console.warn('Events is not an array:', data);
                                data = [];
                            }

                            // Transform events for the calendar with resourceId
                            const formattedEvents = data.map(event => ({
                                id: event.id,
                                title: event.title,
                                start: event.start,
                                end: event.end,
                                resourceId: event.employee_id ? event.employee_id
                                    .toString() : null,
                                extendedProps: {
                                    type: event.type || 'inspection',
                                    status: event.status || 'pending'
                                }
                            }));

                            successCallback(formattedEvents);
                        })
                        .catch(error => {
                            console.error('Error fetching events:', error);
                            // Return empty array on error
                            successCallback([]);
                        });
                }
            });

            calendar.render();

            // Make calendar globally accessible
            window.calendar = calendar;

            // Set up event filters
            setupEventFilters();
        }

        // Set up draggable functionality for inspections
        function setupDraggableEvents() {
            if (!FullCalendar.Draggable) {
                console.error('FullCalendar.Draggable is not available!');
                return;
            }

            const draggables = document.querySelectorAll('.event-card.inspection');

            draggables.forEach(eventCard => {
                try {
                    new FullCalendar.Draggable(eventCard, {
                        itemSelector: '.event-card.inspection',
                        eventData: function(eventEl) {
                            // Get employee ID if assigned
                            const employeeId = eventEl.getAttribute('data-employee-id');

                            // Create event data
                            return {
                                title: eventEl.getAttribute('data-title') || 'Inspection',
                                start: eventEl.getAttribute('data-date') || moment().format(
                                    'YYYY-MM-DD'),
                                id: eventEl.getAttribute('data-id'),
                                resourceId: employeeId,
                                extendedProps: {
                                    type: 'inspection',
                                    status: eventEl.getAttribute('data-status')
                                }
                            };
                        }
                    });

                    // Add dragstart and dragend events
                    eventCard.addEventListener('dragstart', function() {
                        this.classList.add('inspection-being-dragged');
                    });

                    eventCard.addEventListener('dragend', function() {
                        this.classList.remove('inspection-being-dragged');
                    });

                } catch (e) {
                    console.error('Error initializing draggable:', e);
                }
            });
        }

        // Set up event filters
        function setupEventFilters() {
            document.querySelectorAll('.filter-badge').forEach(badge => {
                badge.addEventListener('click', function() {
                    this.classList.toggle('active');
                    applyFilters();
                });
            });

            // Employee checkboxes as filters
            document.querySelectorAll('.employee-selector').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    applyEmployeeFilters();
                });
            });
        }

        // Apply event type filters
        function applyFilters() {
            let activeFilters = [];
            document.querySelectorAll('.filter-badge.active').forEach(badge => {
                activeFilters.push(badge.getAttribute('data-event-type'));
            });

            if (!window.calendar) {
                console.error('Calendar is not initialized');
                return;
            }

            if (activeFilters.length === 0) {
                window.calendar.getEvents().forEach(event => event.setProp('display', 'auto'));
                return;
            }

            window.calendar.getEvents().forEach(event => {
                const eventType = event.extendedProps?.type || '';
                if (activeFilters.includes(eventType)) {
                    event.setProp('display', 'auto');
                } else {
                    event.setProp('display', 'none');
                }
            });
        }

        // Apply employee filters
        function applyEmployeeFilters() {
            let selectedEmployeeIds = [];

            // Get all checked employees
            document.querySelectorAll('.employee-selector:checked').forEach(checkbox => {
                selectedEmployeeIds.push(checkbox.value);
            });

            if (!window.calendar) {
                console.error('Calendar is not initialized');
                return;
            }

            if (selectedEmployeeIds.length === 0) {
                // If no employees selected, show all resources
                window.calendar.getResources().forEach(resource => {
                    resource.setProp('display', 'auto');
                });
                return;
            }

            // Filter resources based on selected employees
            window.calendar.getResources().forEach(resource => {
                if (selectedEmployeeIds.includes(resource.id)) {
                    resource.setProp('display', 'auto');
                } else {
                    resource.setProp('display', 'none');
                }
            });
        }

        // Function to assign inspection to employee via AJAX
        function assignInspectionToEmployee(inspectionId, employeeId) {
            console.log(`Assigning inspection ${inspectionId} to employee ${employeeId}`);

            const requestData = {
                inspection_id: inspectionId,
                employee_id: employeeId
            };

            // Show processing state
            showToast('Assigning inspection...', 'info');

            fetch('/app/tenant/ajax/assignInspection', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify(requestData)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showToast('Inspection assigned successfully', 'success');

                        // Refresh the calendar to reflect the change
                        window.calendar.refetchEvents();

                        // Update the inspection card display if needed
                        const inspectionCard = document.querySelector(
                            `.event-card.inspection[data-id="${inspectionId}"]`);
                        if (inspectionCard) {
                            // Update the employee badge text with the new employee name
                            const employeeBadge = inspectionCard.querySelector('.badge');
                            if (employeeBadge && data.employee_name) {
                                employeeBadge.textContent = data.employee_name;
                            }

                            // Update the data-employee-id attribute
                            inspectionCard.setAttribute('data-employee-id', employeeId);
                        }
                    } else {
                        showToast(data.message || 'Failed to assign inspection', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error assigning inspection:', error);
                    showToast('An error occurred while assigning the inspection', 'error');
                });
        }

        // Promise-based event update function
        function updateEventDataAsync(eventData) {
            return new Promise((resolve, reject) => {
                fetch('/app/tenant/ajax/updateEvent', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Cache-Control': 'no-cache',
                            'Pragma': 'no-cache'
                        },
                        body: JSON.stringify(eventData)
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        resolve(data.success || data.status === 'success');
                    })
                    .catch(error => {
                        console.error('Error updating event:', error);
                        reject(error);
                    });
            });
        }

        // Simplified toast notification function
        function showToast(message, type = 'info') {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `calendar-toast position-fixed top-0 end-0 p-3`;
            toast.style.zIndex = '1070';

            // Set background color based on type
            let bgColor = 'bg-info';
            if (type === 'success') bgColor = 'bg-success';
            if (type === 'error') bgColor = 'bg-danger';
            if (type === 'warning') bgColor = 'bg-warning';

            toast.innerHTML = `
            <div class="toast show text-white ${bgColor}" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto">${type.charAt(0).toUpperCase() + type.slice(1)}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;

            document.body.appendChild(toast);

            // Auto-dismiss after 2 seconds
            setTimeout(() => {
                if (document.body.contains(toast)) {
                    document.body.removeChild(toast);
                }
            }, 2000);
        }
    });
</script>
