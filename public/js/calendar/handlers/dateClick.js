import { SwalConfigs } from '../configs/swalConfigs.js';

export function handleDateClick(info) {
    // Seçilen tarih
    const selectedDate = info.dateStr;
    
    // Modal HTML'i
    const modalHtml = `
    <div class="modal fade" id="newEventModal" tabindex="-1" aria-labelledby="newEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newEventModalLabel">Add New Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="new-event-form">
                        <div class="mb-3">
                            <label for="new-event-title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="new-event-title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="new-event-employee" class="form-label">Employee</label>
                            <select class="form-select" id="new-event-employee" name="employee_id" required>
                                <option value="">Select Employee</option>
                            </select>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="new-event-start" class="form-label">Start</label>
                                <input type="datetime-local" class="form-control" id="new-event-start" name="start_date" value="${selectedDate}T00:00" required>
                            </div>
                            <div class="col">
                                <label for="new-event-end" class="form-label">End</label>
                                <input type="datetime-local" class="form-control" id="new-event-end" name="end_date" value="${selectedDate}T01:00" required>
                            </div>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="new-event-all-day" name="is_all_day">
                            <label class="form-check-label" for="new-event-all-day">All Day</label>
                        </div>
                        <div class="mb-3">
                            <label for="new-event-type" class="form-label">Type</label>
                            <select class="form-select" id="new-event-type" name="type" required>
                                <option value="standard">Standard</option>
                                <option value="vacation">Vacation</option>
                                <option value="sick_leave">Sick Leave</option>
                                <option value="meeting">Meeting</option>
                                <option value="personal">Personal</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="new-event-status" class="form-label">Status</label>
                            <select class="form-select" id="new-event-status" name="status" required>
                                <option value="scheduled">Scheduled</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="new-event-description" class="form-label">Description</label>
                            <textarea class="form-control" id="new-event-description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="new-event-color" class="form-label">Color</label>
                            <input type="color" class="form-control form-control-color" id="new-event-color" name="color" value="#3788d8">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="new-event-private" name="is_private">
                            <label class="form-check-label" for="new-event-private">Private</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-event-btn">Save</button>
                </div>
            </div>
        </div>
    </div>
    `;
    
    // Modal'ı DOM'a ekle
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Modal nesnesini oluştur
    const modalElement = document.getElementById('newEventModal');
    const modal = new bootstrap.Modal(modalElement);
    
    // Çalışanları yükle
    loadEmployees();
    
    // Event tipini değiştirme işlevi - renk seçimini otomatik güncelle
    document.getElementById('new-event-type').addEventListener('change', function() {
        const colorSelect = document.getElementById('new-event-color');
        
        switch(this.value) {
            case 'vacation':
                colorSelect.value = '#28a745'; // Yeşil
                break;
            case 'sick_leave':
                colorSelect.value = '#dc3545'; // Kırmızı
                break;
            case 'meeting':
                colorSelect.value = '#fd7e14'; // Turuncu
                break;
            case 'personal':
                colorSelect.value = '#6f42c1'; // Mor
                break;
            default:
                colorSelect.value = '#3788d8'; // Mavi (varsayılan)
        }
    });
    
    // All day checkbox change handler
    document.getElementById('new-event-all-day').addEventListener('change', function() {
        const startInput = document.getElementById('new-event-start');
        const endInput = document.getElementById('new-event-end');
        
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
            startInput.addEventListener('change', updateEndDateForAllDay);
            
            // Don't make end date read-only, just disable it visually
            endInput.disabled = true;
        } else {
            // If all day is not selected, enable both inputs
            startInput.removeEventListener('change', updateEndDateForAllDay);
            endInput.disabled = false;
        }
    });
    
    // Function to update end date when start date changes (for all day events)
    function updateEndDateForAllDay() {
        const startInput = document.getElementById('new-event-start');
        const endInput = document.getElementById('new-event-end');
        
        const startDate = new Date(startInput.value);
        
        // Set end time to 23:59 of the same day as start
        endInput.value = formatDateForInput(new Date(
            startDate.getFullYear(),
            startDate.getMonth(),
            startDate.getDate(),
            23, 59, 59
        ));
    }
    
    // Format date for input
    function formatDateForInput(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }
    
    // Kaydet butonuna tıklandığında
    document.getElementById('save-event-btn').addEventListener('click', function() {
        const form = document.getElementById('new-event-form');
        
        // Get form data
        const formData = new FormData(form);
        const eventData = {};
        
        // Convert FormData to JSON and log each entry
        for (const [key, value] of formData.entries()) {
            console.log(`Form data: ${key} = ${value}`);
            
            // Set boolean values for checkboxes
            if (key === 'is_all_day' || key === 'is_private') {
                eventData[key] = value === 'on';
            } else if (key === 'start_date' || key === 'end_date') {
                // Convert datetime-local format to MySQL datetime format
                const dateObj = new Date(value);
                if (!isNaN(dateObj.getTime())) {
                    eventData[key] = formatDateForDatabase(dateObj);
                } else {
                    eventData[key] = value;
                }
            } else {
                eventData[key] = value;
            }
        }
        
        // Make sure end_date is set correctly for all-day events
        if (eventData.is_all_day && document.getElementById('new-event-end').disabled) {
            const startDate = new Date(document.getElementById('new-event-start').value);
            const endDate = new Date(
                startDate.getFullYear(),
                startDate.getMonth(),
                startDate.getDate(),
                23, 59, 59
            );
            eventData.end_date = formatDateForDatabase(endDate);
        }
        
        // Make sure status is one of the allowed values
        const validStatuses = [
            'annual_leave', 'sick_leave', 'maternity_leave', 'unpaid_leave',
            'business_trip', 'remote_work', 'overtime', 'training', 'other'
        ];
        
        if (!eventData.status || !validStatuses.includes(eventData.status)) {
            eventData.status = 'other'; // Default to other if not valid
        }
        
        // Prepare meta data
        eventData.meta = {
            color: eventData.color || '#3788d8',
            is_private: eventData.is_private || false
        };
        
        // Remove unnecessary fields
        delete eventData.color;
        delete eventData.is_private;
        
        console.log('Sending event data:', eventData);
        
        // Send to API
        fetch('/api/v1/events', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(eventData)
        })
        .then(response => response.json())
        .then(data => {

            console.log(data);
            if (data.success) {
                // If successful, close modal and refresh calendar
                const modal = bootstrap.Modal.getInstance(document.getElementById('newEventModal'));
                modal.hide();
                
                // Immediately refresh the calendar to show the new event
                refreshCalendar();
            } else {
                // Show error messages
                let errorMessage = 'Er is een fout opgetreden bij het aanmaken van de gebeurtenis.';
                
                if (data.errors) {
                    errorMessage += '<ul>';
                    for (const field in data.errors) {
                        data.errors[field].forEach(error => {
                            errorMessage += `<li>${error}</li>`;
                        });
                    }
                    errorMessage += '</ul>';
                } else if (data.error && data.error.includes('conflicting event')) {
                    errorMessage = 'Deze medewerker heeft al een gebeurtenis gepland in deze periode. Kies een andere tijd.';
                    
                    if (data.conflicting_events && data.conflicting_events.length > 0) {
                        errorMessage += '<ul>';
                        data.conflicting_events.forEach(event => {
                            const start = new Date(event.start_date);
                            const end = new Date(event.end_date);
                            errorMessage += `<li>${event.title}: ${formatDateTime(start)} - ${formatDateTime(end)}</li>`;
                        });
                        errorMessage += '</ul>';
                    }
                } else if (data.message) {
                    errorMessage = data.message;
                    
                    if (data.error) {
                        errorMessage += '<br>' + data.error;
                    }
                }
                
                showModalError(errorMessage);
            }
        })
        .catch(error => {
            console.error('Fout bij het aanmaken van gebeurtenis:', error);
            showModalError('Er is een fout opgetreden bij het aanmaken van de gebeurtenis.');
        });
    });
    
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
    
    // Function to refresh the calendar - simplified version
    function refreshCalendar() {
        console.log('Refreshing calendar...');
        
        try {
            // Global calendar değişkenini kullan
            if (window.globalCalendar && typeof window.globalCalendar.refetchEvents === 'function') {
                console.log('Using window.globalCalendar.refetchEvents()');
                window.globalCalendar.refetchEvents();
                return true;
            }
            
            // Eğer global değişken yoksa, bugün düğmesine tıklayarak zorla yenilemeye çalış
            const todayButton = document.querySelector('.fc-today-button');
            if (todayButton) {
                console.log('Clicking today button to force refresh');
                todayButton.click();
                return true;
            }
            
            // İleri ve geri düğmelerine tıklayarak zorla yenilemeye çalış
            const nextButton = document.querySelector('.fc-next-button');
            const prevButton = document.querySelector('.fc-prev-button');
            
            if (nextButton && prevButton) {
                console.log('Clicking next and then prev button to force refresh');
                nextButton.click();
                setTimeout(() => {
                    prevButton.click();
                }, 100);
                return true;
            }
            
            // Başarısız olursa, sayfayı yenile
            console.log('Could not refresh calendar automatically, reloading page');
            setTimeout(() => {
                window.location.reload();
            }, 500); // Modal'ın kapanmasına izin vermek için kısa bir gecikme
            return false;
        } catch (e) {
            console.error('Error refreshing calendar:', e);
            setTimeout(() => {
                window.location.reload();
            }, 500);
            return false;
        }
    }
    
    // Show a notification with a refresh button
    function showRefreshNotification(message = 'New events are available!') {
        // Create a notification element
        const notification = document.createElement('div');
        notification.className = 'alert alert-info alert-dismissible fade show position-fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '9999';
        notification.innerHTML = `
            <strong>${message}</strong> 
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <div class="mt-2">
                <button class="btn btn-sm btn-primary refresh-btn">Refresh Now</button>
            </div>
        `;
        
        // Add to the document
        document.body.appendChild(notification);
        
        // Add event listener to the refresh button
        notification.querySelector('.refresh-btn').addEventListener('click', function() {
            window.location.reload();
        });
        
        // Remove the notification after 10 seconds
        setTimeout(() => {
            notification.remove();
        }, 10000);
    }
    
    // Show an error notification
    function showErrorNotification(message) {
        // Create a notification element
        const notification = document.createElement('div');
        notification.className = 'alert alert-danger alert-dismissible fade show position-fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '9999';
        notification.innerHTML = `
            <strong>Error!</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <div class="mt-2">
                <button class="btn btn-sm btn-primary refresh-btn">Refresh Page</button>
            </div>
        `;
        
        // Add to the document
        document.body.appendChild(notification);
        
        // Add event listener to the refresh button
        notification.querySelector('.refresh-btn').addEventListener('click', function() {
            window.location.reload();
        });
        
        // Remove the notification after 10 seconds
        setTimeout(() => {
            notification.remove();
        }, 10000);
    }
    
    // Format date for display
    function formatDateTime(date) {
        return date.toLocaleString('nl-NL', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    
    // Modal göster
    modal.show();
    
    // Modal kapatıldığında temizle
    modalElement.addEventListener('hidden.bs.modal', function () {
        modalElement.remove();
    });
}

// Çalışanları yükle
function loadEmployees() {
    fetch('/api/v1/employees')
        .then(response => response.json())
        .then(data => {
            const employeeSelect = document.getElementById('new-event-employee');
            employeeSelect.innerHTML = '<option value="">Select Employee</option>';
            
            // Check the structure of the response
            console.log('API Response:', data);
            
            if (data.success && data.data) {
                // If response has success and data properties
                data.data.forEach(employee => {
                    const option = document.createElement('option');
                    option.value = employee.id;
                    option.textContent = employee.name;
                    employeeSelect.appendChild(option);
                });
            } else if (Array.isArray(data)) {
                // If response is directly an array of employees
                data.forEach(employee => {
                    const option = document.createElement('option');
                    option.value = employee.id;
                    option.textContent = employee.name;
                    employeeSelect.appendChild(option);
                });
            } else {
                showModalError('Error loading employees: Unexpected response format');
                console.error('Unexpected employee data format:', data);
            }
        })
        .catch(error => {
            console.error('Error loading employees:', error);
            showModalError('Error loading employees.');
        });
}

// Modal'da hata mesajı göster
function showModalError(message) {
    const modalBody = document.querySelector('#newEventModal .modal-body');
    
    // Varsa önceki hata mesajını kaldır
    const existingAlert = modalBody.querySelector('.alert');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    // Yeni hata mesajı ekle
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger mt-3';
    alertDiv.role = 'alert';
    alertDiv.innerHTML = message;
    
    // Modal body'nin başına ekle
    modalBody.insertBefore(alertDiv, modalBody.firstChild);
} 