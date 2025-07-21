import { handleEventClick } from './handlers/eventClick.js';
import { handleEventResize } from './handlers/eventResize.js';

// Global calendar değişkeni
window.globalCalendar = null;

const nlLocale = {
    code: 'nl',
    week: {
        dow: 1,
        doy: 4
    },
    buttonText: {
        prev: 'Vorige',
        next: 'Volgende',
        today: 'Vandaag',
        month: 'Maand',
        week: 'Week',
        day: 'Dag',
        list: 'Lijst'
    },
    weekText: 'Wk',
    allDayText: 'Hele dag',
    moreLinkText: 'meer',
    noEventsText: 'Geen evenementen',
    viewHint: '[Wee]k'
};

export function initializeCalendar(handlers) {
    const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        locale: nlLocale,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        views: {
            timeGridWeek: {
                titleFormat: { 
                    week: 'numeric', // "17" (week number)
                    year: 'numeric'
                }
            },
            timeGridDay: {
                titleFormat: { 
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric',
                    omitCommas: true
                }
            },
            listWeek: {
                buttonText: 'Lijst',
                listDayFormat: { 
                    weekday: 'long', 
                    month: 'short',
                    day: 'numeric'
                },
                noEventsText: 'Geen evenementen'
            }
        },
        editable: true,
        selectable: true,
        eventDurationEditable: true,
        eventResize: handleEventResize,
        eventResizable: true,
        eventMinHeight: 30,        // Minimum yükseklik
        eventMinWidth: 30,         // Minimum genişlik
        snapDuration: '00:30:00',  // 30 dakikalık aralıklarla snap
        allDaySlot: true,                    // "All Day" sütununu göster
        allDayText: 'Hele dag',              // Hollandaca "Tüm gün" başlığı
        displayEventTime: true,              // Event saatlerini göster
        displayEventEnd: true,               // Event bitiş saatlerini göster
        events: function (info, successCallback, failureCallback) {
            // Tarihleri ISO formatına çevir ve timezone'u kaldır
            const start = info.start.toISOString().split('T')[0];
            const end = info.end.toISOString().split('T')[0];

            fetch(`/api/v1/events?start=${start}&end=${end}`)
                .then(response => response.json())
                .then(response => {
                    const data = Array.isArray(response) ? response : response.data;
                    
                    console.log(data);
                    const events = data.map(event => ({
                        id: event.id,
                        title: event.title || 'Untitled',
                        start: event.start_date || event.start,
                        end: event.end_date || event.end,
                        allDay: event.is_all_day || false,
                        
                        // Renk ayarları
                        backgroundColor: event.meta.color,
                        borderColor: event.borderColor,
                        textColor: event.textColor,
                        
                        // Diğer özellikler
                        classNames: event.is_all_day ? ['fc-event-all-day'] : [],
                        extendedProps: {
                            employee_id: event.employee_id,
                            description: event.description,
                            status: event.status,
                            // ... diğer özellikler
                        }
                    }));
                    
                    successCallback(events);
                })
                .catch(error => {
                    console.error('Error fetching events:', error);
                    failureCallback(error);
                });
        },
        eventDidMount: function (info) {
            // console.log('Event mounted:', info.event.toPlainObject()); // Mount edilen event'i kontrol et
        },
        eventClick: handleEventClick,  // Etkinliğe tıklandığında
        datesSet: function(info) {
            const titleEl = document.querySelector('.fc-toolbar-title');
            const options = {
                month: 'long',
                year: 'numeric'
            };
            
            switch(info.view.type) {
                case 'dayGridMonth': // Aylık görünüm
                    titleEl.textContent = info.start.toLocaleDateString('nl-NL', {
                        month: 'long',
                        year: 'numeric'
                    }).replace(/^./, m => m.toUpperCase()); // Baş harfi büyük
                    break;

                case 'timeGridWeek': // Haftalık görünüm
                    const startWeek = info.start.toLocaleDateString('nl-NL', {
                        day: 'numeric',
                        month: 'short'
                    });
                    const endWeek = info.end.toLocaleDateString('nl-NL', {
                        day: 'numeric',
                        month: 'short',
                        year: info.start.getFullYear() !== info.end.getFullYear() ? 'numeric' : undefined
                    });
                    titleEl.textContent = `${startWeek} – ${endWeek}`;
                    break;

                case 'timeGridDay': // Günlük görünüm
                    titleEl.textContent = info.start.toLocaleDateString('nl-NL', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    }).replace(/^./, m => m.toUpperCase());
                    break;

                case 'listWeek':
                    titleEl.textContent = info.start.toLocaleDateString('nl-NL', {
                        month: 'long',
                        year: 'numeric'
                    }).replace(/^./, m => m.toUpperCase()) + ' Agenda';
                    break;
            }
        },
        eventContent: function(arg) {
            // Tüm gün olmayan eventler için saat gösterimi
            if (!arg.event.allDay) {
                const start = arg.event.start;
                const end = arg.event.end;
                
                return {
                    html: `
                        <div class="fc-event-main-content">
                            <div class="fc-event-title">${arg.event.title}</div>
                            <div class="fc-event-time">
                                ${start ? start.toLocaleTimeString('nl', { hour: '2-digit', minute: '2-digit' }) : ''}
                                ${end ? ' - ' + end.toLocaleTimeString('nl', { hour: '2-digit', minute: '2-digit' }) : ''}
                            </div>
                        </div>
                    `
                };
            }
            
            // Tüm gün eventler için sadece başlık
            return {
                html: `
                    <div class="fc-event-main-content">
                        <div class="fc-event-title">${arg.event.title}</div>
                    </div>
                `
            };
        },
        ...handlers
    });
    
    // Global değişkene ata
    window.globalCalendar = calendar;
    
    calendar.render();
    
    calendar.on('eventResize', function(info) {
        console.log('Resize başladı:', {
            event: info.event,
            start: info.event.start,
            end: info.event.end,
            delta: info.delta
        });
    });
    
    return calendar;
} 