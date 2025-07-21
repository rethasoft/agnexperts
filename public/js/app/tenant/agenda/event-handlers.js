/**
 * Calendar Event Handlers
 * 
 * This file contains handlers for various calendar events like drop, click, select, etc.
 */

/**
 * Handle dropping an event on the calendar
 * @param {Object} info Drop event information
 */
function handleEventDrop(info) {
    try {
        // Get data from the dropped element
        const eventData = JSON.parse(info.draggedEl.dataset.event);
        
        // Create new event data
        const newEventData = {
            id: 'new-' + Date.now(),
            title: eventData.title,
            start: info.date,
            end: new Date(info.date.getTime() + 2 * 60 * 60 * 1000), // 2 hours duration by default
            color: eventData.color,
            extendedProps: eventData.extendedProps
        };
        
        // If dropped on a resource (employee)
        if (info.resource) {
            newEventData.resourceId = info.resource.id;
            newEventData.extendedProps.assignedTo = info.resource.id;
            
            // Show employee-specific notification
            const employeeName = info.resource.title;
            showNotification('Ingepland',
                `"${eventData.title}" ingepland voor ${employeeName} op ${formatDate(info.date)}`,
                'success');
        } else {
            // Regular notification for calendar drop
            showNotification('Ingepland',
                `"${eventData.title}" ingepland op ${formatDate(info.date)}`,
                'success');
        }
        
        // Add the new event to the calendar
        info.view.calendar.addEvent(newEventData);
    } catch (e) {
        console.error('Error handling event drop:', e);
        showNotification('Error', 'Er is een fout opgetreden bij het slepen van het item', 'danger');
    }
}

/**
 * Handle clicking an event on the calendar
 * @param {Object} info Click event information
 */
function handleEventClick(info) {
    try {
        // Get the event details
        const event = info.event;
        
        // Update the modal with event details
        document.getElementById('event-title').textContent = event.title;
        
        // Set event date
        const startTimeStr = event.start ? formatDateTime(event.start) : ''; 
        const endTimeStr = event.end ? formatDateTime(event.end) : '';
        document.getElementById('event-date').textContent = `${startTimeStr}${endTimeStr ? ' - ' + endTimeStr : ''}`;
        
        // Set description if available
        if (event.extendedProps && event.extendedProps.description) {
            document.getElementById('event-description').textContent = event.extendedProps.description;
        }
        
        // Set location if available
        if (event.extendedProps && event.extendedProps.location) {
            document.getElementById('event-location').textContent = event.extendedProps.location;
        }
        
        // Set assigned to if available
        if (event.extendedProps && event.extendedProps.assignedTo) {
            const resources = info.view.calendar.getResources();
            const resource = resources.find(r => r.id === event.extendedProps.assignedTo);
            if (resource) {
                document.getElementById('event-assigned').textContent = `Toegewezen aan: ${resource.title}`;
            }
        }
        
        // Show the modal
        const eventModal = new bootstrap.Modal(document.getElementById('eventDetailModal'));
        eventModal.show();
    } catch (e) {
        console.error('Error handling event click:', e);
        showNotification('Error', 'Er is een fout opgetreden bij het openen van het event', 'danger');
    }
}

/**
 * Handle selecting a date or date range on the calendar
 * @param {Object} info Selection information
 */
function handleDateSelect(info) {
    try {
        // Pre-fill dates when creating a new event by selecting a time range
        const startDateField = document.getElementById('start_date');
        const endDateField = document.getElementById('end_date');
        
        if (startDateField && endDateField) {
            startDateField.value = toDateTimeLocal(info.start);
            endDateField.value = toDateTimeLocal(info.end);
            
            // Show create modal
            const createModal = new bootstrap.Modal(document.getElementById('createEventModal'));
            createModal.show();
        }
    } catch (e) {
        console.error('Error handling date selection:', e);
    }
}

/**
 * Custom rendering for event content on the calendar
 * @param {Object} arg Event rendering information
 * @returns {Object} Custom DOM elements for the event
 */
function renderEventContent(arg) {
    const eventType = arg.event.extendedProps.type || 'default';
    let iconClass = '';
    
    // Set icon based on event type
    switch(eventType) {
        case 'inspection':
            iconClass = 'ri-file-list-line';
            break;
        case 'task':
            iconClass = 'ri-task-line';
            break;
        case 'meeting':
            iconClass = 'ri-team-line';
            break;
        case 'deadline':
            iconClass = 'ri-timer-line';
            break;
        default:
            iconClass = 'ri-calendar-event-line';
    }
    
    // Create the HTML content
    const content = document.createElement('div');
    content.innerHTML = `
        <div class="fc-event-title">
            <i class="${iconClass} me-1"></i>
            <span>${arg.event.title}</span>
        </div>
        <div class="fc-event-time small">
            ${arg.timeText}
        </div>
    `;
    
    return { domNodes: [content] };
}

/**
 * Handle moving events around on the calendar (drag and drop)
 * @param {Object} info Event move information
 */
function handleEventMove(info) {
    try {
        // If moved to a resource
        if (info.newResource) {
            info.event.setExtendedProp('assignedTo', info.newResource.id);
            
            const employeeName = info.newResource.title;
            const eventTitle = info.event.title;
            showNotification('Toegewezen', `"${eventTitle}" toegewezen aan ${employeeName}`, 'success');
        }
    } catch (e) {
        console.error('Error handling event move:', e);
    }
}

/**
 * Convert Date object to datetime-local input format
 * @param {Date} date Date to convert
 * @returns {string} Formatted datetime string
 */
function toDateTimeLocal(date) {
    const year = date.getFullYear();
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const day = date.getDate().toString().padStart(2, '0');
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');
    
    return `${year}-${month}-${day}T${hours}:${minutes}`;
}

/**
 * Format date for display in notifications
 * @param {Date} date Date to format
 * @returns {string} Formatted date string
 */
function formatDate(date) {
    return date.toLocaleDateString('nl-NL', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

/**
 * Format date and time for display
 * @param {Date} date Date to format
 * @returns {string} Formatted date and time string
 */
function formatDateTime(date) {
    if (!date) return '';
    return date.toLocaleDateString('nl-NL', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}