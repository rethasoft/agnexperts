import { SwalConfigs } from '../configs/swalConfigs.js';

export function handleEventClick(info) {
    // Etkinlik verilerini al
    const event = info.event;
    const eventId = event.id;
    
    // Kullanıcı rolünü al (bu bilgiyi sayfada bir meta tag veya global değişken olarak saklayabilirsiniz)
    const userRole = document.querySelector('meta[name="user-role"]')?.getAttribute('content') || 'employee';

    console.log(userRole);
    
    // Etkinlik verilerini API'den çek
    fetch(`/api/v1/events/${eventId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const eventData = data.data;
                
                // API yanıtını detaylı olarak logla
                console.log('API Response:', data);
                console.log('Event Data:', eventData);
                
                // Kullanıcı rolüne göre farklı modal göster
                if (userRole === 'tenant') {
                    // Admin için düzenleme modalını göster
                    openEditModal(eventData);
                } else {
                    // Çalışan için sadece görüntüleme modalını göster
                    openViewModal(eventData);
                }
            } else {
                throw new Error(data.message || 'Error fetching event data');
            }
        })
        .catch(error => {
            console.error('Error fetching event details:', error);
            // Hata durumunda kullanıcıya bildir
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error fetching event details: ' + error.message
            });
        });
}

// Sadece görüntüleme için modal
function openViewModal(eventData) {
    console.log('Event data for viewing:', eventData);
    
    // Format dates
    
    const eventStart = new Date(eventData.start);
    const eventEnd = new Date(eventData.end);
    const eventAllDay = eventData.is_all_day;
    const eventTitle = eventData.title || 'Untitled Event';
    
    // Modal HTML
    const modalHtml = `
    <div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: ${eventData.meta?.color || '#3788d8'}; color: white;">
                    <h5 class="modal-title" id="eventDetailsModalLabel">${eventTitle}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Start:</strong> ${formatDateTime(eventStart, eventAllDay)}
                    </div>
                    <div class="mb-3">
                        <strong>End:</strong> ${formatDateTime(eventEnd, eventAllDay)}
                    </div>
                    <div class="mb-3">
                        <strong>Employee:</strong> ${eventData.employee?.name || 'Not specified'}
                    </div>
                    <div class="mb-3">
                        <strong>Type:</strong> ${formatEventType(eventData.type)}
                    </div>
                    <div class="mb-3">
                        <strong>Status:</strong> ${formatEventStatus(eventData.status)}
                    </div>
                    ${eventData.description ? `
                    <div class="mb-3">
                        <strong>Description:</strong>
                        <p>${eventData.description}</p>
                    </div>
                    ` : ''}
                    ${eventData.meta?.notes ? `
                    <div class="mb-3">
                        <strong>Notes:</strong>
                        <p>${eventData.meta.notes}</p>
                    </div>
                    ` : ''}
                </div>
                <div class="modal-footer">
                 <button type="button" class="btn btn-primary edit-event-btn" data-id="${eventData.id}">Edit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    `;
    
    // Add modal to DOM
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Create modal instance
    const modalElement = document.getElementById('eventDetailsModal');
    const modal = new bootstrap.Modal(modalElement);
    
    // Show modal
    modal.show();
    
    // Clean up when modal is closed
    modalElement.addEventListener('hidden.bs.modal', function () {
        modalElement.remove();
    });
}

function formatDate(date) {
    let year = date.getFullYear();
    let month = String(date.getMonth() + 1).padStart(2, '0');
    let day = String(date.getDate()).padStart(2, '0');
    let hours = String(date.getHours()).padStart(2, '0');
    let minutes = String(date.getMinutes()).padStart(2, '0');
    let seconds = String(date.getSeconds()).padStart(2, '0');
    
    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}
// Open edit modal
function openEditModal(eventData) {
    
    // API'den eksik veriler geliyorsa, FullCalendar'dan gelen verileri kullan
    if (!eventData.start_date && window.globalCalendar) {
        const calendarEvent = window.globalCalendar.getEventById(eventData.id);
        if (calendarEvent) {
            console.log('Using calendar event data:', calendarEvent);
            
            console.log(calendarEvent);
            // FullCalendar event verilerini kullan
            eventData.start_date = formatDate(calendarEvent.start);
            eventData.end_date = formatDate(calendarEvent.end || calendarEvent.start);
            eventData.is_all_day = calendarEvent.allDay;
            eventData.title = calendarEvent.title;
            
            // Diğer eksik alanlar için varsayılan değerler
            eventData.status = eventData.status || 'other';
            eventData.description = eventData.description || '';
            eventData.meta = eventData.meta || { color: '#3788d8', is_private: false };
        }
    }
    
    // Format dates for input
    const startDate = new Date(eventData.start_date);
    const endDate = new Date(eventData.end_date);
    
    const formattedStartDate = formatDateForInput(startDate);
    const formattedEndDate = formatDateForInput(endDate);
    
    
    // Modal HTML
    const modalHtml = `
    <div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEventModalLabel">Edit Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-event-form">
                        <input type="hidden" name="id" value="${eventData.id}">
                        <div class="mb-3">
                            <label for="edit-event-title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="edit-event-title" name="title" value="${eventData.title || ''}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-event-employee" class="form-label">Employee</label>
                            <select class="form-select" id="edit-event-employee" name="employee_id" required>
                                <option value="">Loading...</option>
                            </select>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="edit-event-start" class="form-label">Start</label>
                                <input type="datetime-local" class="form-control" id="edit-event-start" name="start_date" value="${formattedStartDate}" required>
                            </div>
                            <div class="col">
                                <label for="edit-event-end" class="form-label">End</label>
                                <input type="datetime-local" class="form-control" id="edit-event-end" name="end_date" value="${formattedEndDate}" required>
                            </div>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="edit-event-all-day" name="is_all_day" ${eventData.is_all_day ? 'checked' : ''}>
                            <label class="form-check-label" for="edit-event-all-day">All Day</label>
                        </div>
                        <div class="mb-3">
                            <label for="edit-event-status" class="form-label">Status</label>
                            <select class="form-select" id="edit-event-status" name="status" required>
                                <option value="annual_leave" ${eventData.status === 'annual_leave' ? 'selected' : ''}>Annual Leave</option>
                                <option value="sick_leave" ${eventData.status === 'sick_leave' ? 'selected' : ''}>Sick Leave</option>
                                <option value="maternity_leave" ${eventData.status === 'maternity_leave' ? 'selected' : ''}>Maternity Leave</option>
                                <option value="unpaid_leave" ${eventData.status === 'unpaid_leave' ? 'selected' : ''}>Unpaid Leave</option>
                                <option value="business_trip" ${eventData.status === 'business_trip' ? 'selected' : ''}>Business Trip</option>
                                <option value="remote_work" ${eventData.status === 'remote_work' ? 'selected' : ''}>Remote Work</option>
                                <option value="overtime" ${eventData.status === 'overtime' ? 'selected' : ''}>Overtime</option>
                                <option value="training" ${eventData.status === 'training' ? 'selected' : ''}>Training</option>
                                <option value="other" ${eventData.status === 'other' ? 'selected' : ''}>Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit-event-description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit-event-description" name="description" rows="3">${eventData.description || ''}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit-event-color" class="form-label">Color</label>
                            <input type="color" class="form-control form-control-color" id="edit-event-color" name="color" value="${eventData.meta?.color || '#3788d8'}">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="edit-event-private" name="is_private" ${eventData.meta?.is_private ? 'checked' : ''}>
                            <label class="form-check-label" for="edit-event-private">Private</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="update-event-btn">Update</button>
                </div>
            </div>
        </div>
    </div>
    `;
    
    // Add modal to DOM
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Create modal instance
    const modalElement = document.getElementById('editEventModal');
    const modal = new bootstrap.Modal(modalElement);
    
    // Load employees
    loadEmployeesForEdit(eventData.employee.id);
    
    // Show modal
    modal.show();
    
    // Add event listeners after modal is shown
    modalElement.addEventListener('shown.bs.modal', function() {
        // Log form values for debugging
        console.log('Form values after modal shown:');
        console.log('Start date:', document.getElementById('edit-event-start')?.value);
        console.log('End date:', document.getElementById('edit-event-end')?.value);
        console.log('All day:', document.getElementById('edit-event-all-day')?.checked);
        
        // Event type change handler - automatically update color
        const eventTypeSelect = document.getElementById('edit-event-type');
        if (eventTypeSelect) {
            eventTypeSelect.addEventListener('change', function() {
                const colorSelect = document.getElementById('edit-event-color');
                if (colorSelect) {
                    switch(this.value) {
                        case 'vacation':
                            colorSelect.value = '#28a745'; // Green
                            break;
                        case 'sick_leave':
                            colorSelect.value = '#dc3545'; // Red
                            break;
                        case 'meeting':
                            colorSelect.value = '#fd7e14'; // Orange
                            break;
                        case 'personal':
                            colorSelect.value = '#6f42c1'; // Purple
                            break;
                        default:
                            colorSelect.value = '#3788d8'; // Blue (default)
                    }
                }
            });
        }
        
        // All day checkbox change handler
        const allDayCheckbox = document.getElementById('edit-event-all-day');
        if (allDayCheckbox) {
            allDayCheckbox.addEventListener('change', function() {
                const startInput = document.getElementById('edit-event-start');
                const endInput = document.getElementById('edit-event-end');
                
                if (startInput && endInput) {
                    if (this.checked) {
                        // If all day is selected, set start and end times to full day
                        const startDate = new Date(startInput.value);
                        
                        // Set start time to 00:00
                        startInput.value = formatDateForInput(new Date(
                            startDate.getFullYear(),
                            startDate.getMonth(),
                            startDate.getDate(),
                            0, 0, 0
                        ));
                        
                        // Set end time to 23:59 of the same day
                        endInput.value = formatDateForInput(new Date(
                            startDate.getFullYear(),
                            startDate.getMonth(),
                            startDate.getDate(),
                            23, 59, 59
                        ));
                        
                        // Keep start date enabled, but automatically update end date when start changes
                        startInput.addEventListener('change', updateEndDateForAllDayEdit);
                        
                        // Don't make end date read-only, just disable it visually
                        endInput.readOnly = true;
                    } else {
                        // If all day is not selected, enable both inputs
                        startInput.removeEventListener('change', updateEndDateForAllDayEdit);
                        endInput.readOnly = false;
                    }
                }
            });
        }
        
        // Update button click handler
        const updateBtn = document.getElementById('update-event-btn');
        if (updateBtn) {
            updateBtn.addEventListener('click', function() {
                const form = document.getElementById('edit-event-form');
                if (form) {
                    // Get form data
                    const formData = new FormData(form);
                    const eventData = {};
                    
                    // Convert FormData to JSON
                    for (const [key, value] of formData.entries()) {
                        if (key === 'is_all_day' || key === 'is_private') {
                            eventData[key] = true; // Checkbox is checked
                        } else {
                            eventData[key] = value;
                        }
                    }
                    
                    // Handle checkboxes that might not be in the FormData if unchecked
                    if (!formData.has('is_all_day')) {
                        eventData.is_all_day = false;
                    }
                    
                    if (!formData.has('is_private')) {
                        eventData.is_private = false;
                    }
                    
                    // Add meta data
                    eventData.meta = {
                        color: formData.get('color'),
                        is_private: eventData.is_private
                    };
                                    
                    eventData.start_date = formatDateForBackend(eventData.start_date);
                    eventData.end_date = formatDateForBackend(eventData.end_date);
                    // Remove is_private from root level
                    delete eventData.is_private;
                
                    
                    // Send update request to API
                    fetch(`/api/v1/events/${eventData.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(eventData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // If successful, close modal and refresh calendar
                            modal.hide();
                            
                            // Refresh calendar immediately
                            if (window.globalCalendar) {
                                window.globalCalendar.refetchEvents();
                            } else {
                                // Fallback to page reload
                                window.location.reload();
                            }
                        } else {
                            showEditModalError('Error updating event: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error updating event:', error);
                        showEditModalError('Error updating event. Please try again.');
                    });
                }
            });
        }
        
        // Trigger all day checkbox if needed
        if (eventData.is_all_day) {
            const allDayCheckbox = document.getElementById('edit-event-all-day');
            if (allDayCheckbox) {
                allDayCheckbox.checked = true;
                allDayCheckbox.dispatchEvent(new Event('change'));
            }
        }
    });
    
    // Clean up when modal is closed
    modalElement.addEventListener('hidden.bs.modal', function () {
        modalElement.remove();
    });
}

// Function to update end date when start date changes (for all day events)
function updateEndDateForAllDayEdit() {
    const startInput = document.getElementById('edit-event-start');
    const endInput = document.getElementById('edit-event-end');
    
    if (startInput && endInput) {
        const startDate = new Date(startInput.value);
        
        // Set end time to 23:59 of the same day as start
        endInput.value = formatDateForInput(new Date(
            startDate.getFullYear(),
            startDate.getMonth(),
            startDate.getDate(),
            23, 59, 59
        ));
    }
}

// Load employees for edit modal
function loadEmployeesForEdit(selectedEmployeeId) {
    fetch('/api/v1/employees')
        .then(response => response.json())
        .then(data => {
            const employeeSelect = document.getElementById('edit-event-employee');
            employeeSelect.innerHTML = '<option value="">Select Employee</option>';
            
            
            if (data.success && data.data) {
                // If response has success and data properties
                data.data.forEach(employee => {
                    const option = document.createElement('option');
                    option.value = employee.id;
                    option.textContent = employee.name;
                    
                    if (employee.id == selectedEmployeeId) {
                        option.selected = true;
                    }
                    
                    employeeSelect.appendChild(option);
                });
            } else if (Array.isArray(data)) {
                // If response is directly an array of employees
                data.forEach(employee => {
                    const option = document.createElement('option');
                    option.value = employee.id;
                    option.textContent = employee.name;
                    
                    if (employee.id == selectedEmployeeId) {
                        option.selected = true;
                    }
                    
                    employeeSelect.appendChild(option);
                });
            } else {
                showEditModalError('Error loading employees: Unexpected response format');
                console.error('Unexpected employee data format:', data);
            }
        })
        .catch(error => {
            console.error('Error loading employees:', error);
            showEditModalError('Error loading employees.');
        });
}

// Show error message in edit modal
function showEditModalError(message) {
    const modalBody = document.querySelector('#editEventModal .modal-body');
    
    // Remove existing alert if any
    const existingAlert = modalBody.querySelector('.alert');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    // Add new alert
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger mt-3';
    alertDiv.role = 'alert';
    alertDiv.innerHTML = message;
    
    // Insert at the beginning of modal body
    modalBody.insertBefore(alertDiv, modalBody.firstChild);
}

// Format date and time
function formatDateTime(date, isAllDay) {
    if (!date) return 'Not specified';
    
    const options = isAllDay 
        ? { year: 'numeric', month: 'long', day: 'numeric' }
        : { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    
    return new Date(date).toLocaleString('en-US', options);
}

// Format date for input
function formatDateForInput(date) {
    if (!date || isNaN(date.getTime())) {
        console.error('Invalid date:', date);
        return '';
    }
    
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    
    return `${year}-${month}-${day}T${hours}:${minutes}`;
}

// Format date for database (MySQL datetime format)
function formatDateForDatabase(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');
    
    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

// Format event type
function formatEventType(type) {
    const types = {
        'standard': 'Standard',
        'vacation': 'Vacation',
        'sick_leave': 'Sick Leave',
        'meeting': 'Meeting',
        'personal': 'Personal'
    };
    
    return types[type] || type;
}

// Format event status
function formatEventStatus(status) {
    // Eğer status bir nesne ise
    if (status && typeof status === 'object') {

        // Nesnenin içinde name veya label gibi bir özellik var mı kontrol et
        if (status.name) return status.name;
        if (status.label) return status.label;
        if (status.value) return status.value;
        
        // Nesneyi JSON string'e dönüştür (debug için)
        return JSON.stringify(status);
    }
    
    // Status bir string ise
    const statuses = {
        'annual_leave': 'Annual Leave',
        'sick_leave': 'Sick Leave',
        'maternity_leave': 'Maternity Leave',
        'unpaid_leave': 'Unpaid Leave',
        'business_trip': 'Business Trip',
        'remote_work': 'Remote Work',
        'overtime': 'Overtime',
        'training': 'Training',
        'other': 'Other',
        'scheduled': 'Scheduled',
        'confirmed': 'Confirmed',
        'completed': 'Completed',
        'cancelled': 'Cancelled'
    };
    
    return statuses[status] || status;
}

// Function to refresh the calendar
function refreshCalendar() {
    try {
        // Find the FullCalendar instance
        const calendarEl = document.querySelector('.fc');
        if (calendarEl) {
            // Get the FullCalendar API
            const viewHarness = calendarEl.querySelector('.fc-view-harness');
            if (viewHarness && viewHarness._fullCalendar) {
                viewHarness._fullCalendar.refetchEvents();
                return;
            }
        }
        
        // If we couldn't find the calendar API through DOM, try global variables
        if (typeof fullCalendar !== 'undefined') {
            fullCalendar.refetchEvents();
            console.log('Calendar refreshed through global fullCalendar variable');
            return;
        }
        
        // Try to access through window.myCalendar
        if (window.myCalendar && typeof window.myCalendar.getCalendarInstance === 'function') {
            const calendarInstance = window.myCalendar.getCalendarInstance();
            if (calendarInstance) {
                calendarInstance.refetchEvents();
                return;
            }
        }
        
        // As a last resort, try to find any FullCalendar instance
        const fcRoot = document.querySelector('.fc-root');
        if (fcRoot && fcRoot._fullCalendar) {
            fcRoot._fullCalendar.refetchEvents();
            console.log('Calendar refreshed through fc-root');
            return;
        }
        
        // If all else fails, use a more direct approach: fetch events from server and manually update
        fetch('/api/v1/calendar/events')
            .then(response => response.json())
            .then(eventsData => {
                // Find any FullCalendar instance and update its events
                const fcElements = document.querySelectorAll('.fc');
                for (const fcEl of fcElements) {
                    if (fcEl._fullCalendar) {
                        fcEl._fullCalendar.removeAllEvents();
                        fcEl._fullCalendar.addEventSource(eventsData);
                        console.log('Calendar updated manually with new events');
                        return;
                    }
                }
                
                // If we still can't find a way to update, reload as last resort
                console.log('Could not find any way to refresh calendar, reloading page...');
                window.location.reload();
            })
            .catch(err => {
                console.error('Error fetching events:', err);
                window.location.reload();
            });
    } catch (e) {
        console.error('Error refreshing calendar:', e);
        // If all else fails, reload the page but with a delay to allow modal to close
        setTimeout(() => {
            window.location.reload();
        }, 500);
    }
}

// Format date for display
function formatEventDateTime(date) {
    return date.toLocaleString('nl-NL', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function formatDateForBackend(dateTimeString) {
    if (!dateTimeString) return null;
    return dateTimeString.replace('T', ' ') + ':00';
} 