// Ana calendar dosyası
import { initializeCalendar } from './initialize.js';
import { handleEventClick } from './handlers/eventClick.js';
import { handleEventDrop } from './handlers/eventDrop.js';
import { handleDateClick } from './handlers/dateClick.js';
import { handleEventResize } from './handlers/eventResize.js';
import { SwalConfigs } from './configs/swalConfigs.js';

document.addEventListener('DOMContentLoaded', function() {
    const calendar = initializeCalendar({
        eventClick: handleEventClick,
        eventDrop: handleEventDrop,
        dateClick: handleDateClick,
        eventResize: handleEventResize,
        // ... diğer calendar konfigürasyonları
    });

    calendar.render();
}); 