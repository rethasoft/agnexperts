/**
 * Calendar Initialization Module
 * 
 * This file handles the initialization of the FullCalendar component
 */

/**
 * Initialize the calendar with provided resources and events
 * @param {Array} resources Array of employee resources
 * @param {Array} events Array of calendar events
 * @returns {Object} The calendar instance
 */
function initializeCalendar(resources, events) {
    // Get calendar element
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) {
        console.error("Calendar element not found");
        return null;
    }
    
    // Create calendar instance
    const calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: ['interaction', 'dayGrid', 'timeGrid', 'resourceTimeline'],
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        locale: 'nl',
        editable: true,
        selectable: true,
        selectMirror: true,
        dayMaxEvents: true,
        droppable: true, // Enable dropping on the calendar
        
        // Resource view specific options
        resourceAreaWidth: '15%',
        resourceAreaHeaderContent: 'Medewerkers',
        resourceGroupField: 'role',
        resources: resources,
        
        // Add resource-specific options to existing views
        views: {
            resourceTimelineWeek: {
                type: 'resourceTimeline',
                duration: { days: 7 },
                slotDuration: '01:00:00',
                businessHours: true,
                expandRows: true,
                resourcesInitiallyExpanded: true,
                resourceAreaColumns: [{
                    field: 'title',
                    headerContent: 'Medewerkers',
                    width: 150
                }]
            }
        },
        
        // Event handlers
        drop: handleEventDrop,
        events: events,
        eventClick: handleEventClick,
        select: handleDateSelect,
        eventContent: renderEventContent,
        eventDrop: handleEventMove
    });
    
    // Render the calendar
    calendar.render();
    
    // Return the calendar instance
    return calendar;
}

/**
 * Set up calendar filter buttons
 * @param {Object} calendar The calendar instance
 */
function setupFilters(calendar) {
    document.querySelectorAll('.filter-badge').forEach(function(badge) {
        badge.addEventListener('click', function() {
            // Toggle active class
            this.classList.toggle('active');
            
            // Get all selected filters
            const activeFilters = [];
            document.querySelectorAll('.filter-badge.active').forEach(function(activeBadge) {
                activeFilters.push(activeBadge.getAttribute('data-filter'));
            });
            
            // Filter events
            calendar.getEvents().forEach(function(event) {
                const eventType = event.extendedProps.type;
                if (activeFilters.length === 0 || activeFilters.includes(eventType)) {
                    event.setProp('display', 'auto');
                } else {
                    event.setProp('display', 'none');
                }
            });
        });
    });
}

/**
 * Set up calendar view buttons
 * @param {Object} calendar The calendar instance
 */
function setupViewButtons(calendar) {
    document.querySelectorAll('.view-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            document.querySelectorAll('.view-btn').forEach(function(b) {
                b.classList.remove('active');
            });
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Change calendar view
            calendar.changeView(this.getAttribute('data-view'));
        });
    });
}