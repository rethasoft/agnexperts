/**
 * Calendar Draggable Initialization Module
 * 
 * This file handles the initialization of draggable elements 
 * that can be dropped onto the calendar
 */

/**
 * Initialize draggable events in the sidebar
 */
function initializeDraggableEvents() {
    // Log for debugging
    console.log('Initializing draggable events');
    
    // Get all draggable containers
    const containers = document.querySelectorAll('.sidebar-draggable');
    console.log(`Found ${containers.length} draggable containers`);
    
    containers.forEach(function(container, index) {
        // Log container info
        const eventCards = container.querySelectorAll('.event-card');
        console.log(`Container ${index}: contains ${eventCards.length} event cards`);
        
        try {
            // Initialize draggable
            new FullCalendar.Draggable(container, {
                itemSelector: '.event-card',
                eventData: function(eventEl) {
                    try {
                        // Get event data from data-event attribute
                        const eventData = eventEl.dataset.event;
                        console.log(`Dragging event with data: ${eventData}`);
                        return JSON.parse(eventData);
                    } catch (e) {
                        console.error("Error parsing event data:", e);
                        console.error("Raw event data:", eventEl.dataset.event);
                        return {
                            title: "Error parsing event data",
                            color: "#dc3545"
                        };
                    }
                },
                revertDuration: 150
            });
            console.log(`Draggable initialized for container ${index}`);
        } catch (error) {
            console.error(`Failed to initialize draggable for container ${index}:`, error);
        }
    });
}