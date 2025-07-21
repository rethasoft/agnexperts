class MyCalendar {
    constructor(calendarId) {
        this.calendarId = calendarId;
        this.addOffDayUrl = '/app/tenant/employe-event';
        this.employees = [];
        this.calendar = null; // Calendar instance'ı için property
        this.init();
    }

    fetchEmployees() {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '/app/tenant/ajax/getEmployes', // Modify with your actual API endpoint
                method: 'GET',
                success: (response) => {
                    resolve(response); // Adjust depending on response structure
                },
                error: (error) => {
                    console.error("Error fetching employees:", error);
                    reject(error);
                }
            });
        });
    }

    headerToolBar() {
        return {
            left: 'prev,next today',
            center: 'title',
            right: 'resourceTimeGridDay,timeGridWeek,dayGridMonth'
        };
    }

    sendRequest(title, startDate, endDate, selectedEmployeeId, method = 'POST') {
        var self = this;
        $.ajax({
            url: self.addOffDayUrl,
            type: method,
            data: {
                title: title,
                start_date: startDate,
                end_date: endDate,
                employee_id: selectedEmployeeId // Get selected employee id from your form
            },
            success: function (response) {
            },
            error: function (xhr, status, error) {
            }
        });
    }


    initCalendar() {
        const calendarEl = document.getElementById(this.calendarId);
        this.calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            editable: true,
            headerToolbar: this.headerToolBar(),
            selectable: true,
            eventResizableFromStart: true, // Allow resizing from the start of the event
            eventDurationEditable: true, // Allow duration change by dragging the end
            firstDay: 1,
            eventRender: function (event, element) {
                // Customize event element to adjust height
                element.css('height', 'auto'); // Set height to auto to accommodate content
            },
            events: (info, successCallback, failureCallback) => {
                const start = info.startStr.split('T')[0];
                const end = info.endStr.split('T')[0];

                fetch(`/api/v1/events?start=${start}&end=${end}`)
                    .then(response => response.json())
                    .then(response => {
                        if (response.success) {
                            // Event verilerini işle ve renkleri status'a göre ayarla
                            const events = response.data.map(event => ({
                                ...event,
                                title: `${event.title} - ${event.employee?.name || 'N/A'}`,
                                backgroundColor: `var(--bs-${event.status.color})`, // Bootstrap renk değişkenini kullan
                                borderColor: `var(--bs-${event.status.color})`,    // Kenarlık rengini de ayarla
                                textColor: event.status.color === 'warning' ? 'var(--bs-dark)' : 'var(--bs-white)', // Koyu arka plan renkleri için beyaz yazı
                                classNames: [`event-${event.status.value}`], // CSS class ekle
                                extendedProps: {
                                    ...event.extendedProps,
                                    status: event.status
                                }
                            }));
                            successCallback(events);
                        } else {
                            failureCallback(new Error(response.message));
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching events:", error);
                        failureCallback(error);
                    });
            },
            select: (info) => {
                this.popup(info.startStr, info.endStr, this.calendar);
            },
            eventClick: (info) => {
                const event = info.event;

                // Tarih formatını ayarla
                const startDate = new Date(event.start).toLocaleString('nl-NL', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                // Event tipine göre ikon ve renk belirle
                const typeIcons = {
                    'inspection': { icon: 'clipboard-line', color: 'text-success', label: 'Inspection' },
                    'leave': { icon: 'sun-line', color: 'text-warning', label: 'Leave' },
                    'sick': { icon: 'heart-pulse-line', color: 'text-danger', label: 'Sick Leave' }
                };
                const typeInfo = typeIcons[event.extendedProps.type] || {
                    icon: 'calendar',
                    color: 'text-primary',
                    label: 'Event'
                };

                // Status badge'i için renk belirle
                const statusColors = {
                    'scheduled': 'primary',
                    'completed': 'success',
                    'cancelled': 'danger',
                    'pending': 'warning'
                };
                const statusColor = statusColors[event.extendedProps.status] || 'secondary';

                // HTML içeriğini oluştur
                let modalContent = `
                    <div class="event-detail-container">
                        <!-- Header Section -->
                        <div class="event-header mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="ri-${typeInfo.icon} ${typeInfo.color} ri-lg me-3"></i>
                                <div>
                                    <h4 class="mb-1">${event.title}</h4>
                                    <span class="text-muted">${typeInfo.label}</span>
                                </div>
                            </div>
                            <span class="badge bg-${event.extendedProps.status.color} px-3 py-2 mt-2">
                                ${event.extendedProps.status.label}
                            </span>
                        </div>

                        <!-- Details Section -->
                        <div class="event-body">
                            <div class="info-card mb-3">
                                <!-- Zaman Bilgisi -->
                                <div class="info-item">
                                    <i class="ri-time-line text-muted"></i>
                                    <div class="info-content">
                                        <label>Date & Time</label>
                                        <span>${startDate}</span>
                                    </div>
                                </div>

                                <!-- Çalışan Bilgisi -->
                                <div class="info-item">
                                    <i class="ri-user-line text-muted"></i>
                                    <div class="info-content">
                                        <label>Employee</label>
                                        <span>${event.extendedProps.employee?.name || 'N/A'}</span>
                                    </div>
                                </div>`;

                // Açıklama varsa ekle
                if (event.extendedProps.description) {
                    modalContent += `
                        <div class="info-item">
                            <i class="ri-align-left text-muted"></i>
                            <div class="info-content">
                                <label>Description</label>
                                <span>${event.extendedProps.description}</span>
                            </div>
                        </div>`;
                }

                // Inspection tipine özel alanlar
                if (event.extendedProps.type === 'inspection') {
                    modalContent += `
                        <!-- <div class="info-item">
                            <i class="ri-map-pin-line text-muted"></i>
                            <div class="info-content">
                                <label>Location</label>
                                <span>${event.extendedProps.location || 'Not specified'}</span>
                            </div>
                        </div> -->
                        ${event.extendedProps.file_id ? `
                        <div class="info-item">
                            <i class="ri-file-line text-muted"></i>
                            <div class="info-content">
                                <label>Project Number</label>
                                <span>
                                    <a href="/tenant/keuringen/${event.extendedProps.keuringen_id}/edit" 
                                       target="_blank" 
                                       class="text-primary">
                                        ${event.extendedProps.file_id}
                                    </a>
                                </span>
                            </div>
                        </div>` : ''}`;
                }

                // Leave tipine özel alanlar
                if (event.extendedProps.type === 'leave') {
                    modalContent += `
                        <div class="info-item">
                            <i class="ri-calendar-line text-muted"></i>
                            <div class="info-content">
                                <label>Leave Type</label>
                                <span>${event.extendedProps.leave_type || 'Regular Leave'}</span>
                            </div>
                        </div>`;
                }

                modalContent += `
                            </div>
                        </div>
                    </div>`;

                // SweetAlert2 modal'ı göster
                Swal.fire({
                    title: false,
                    html: modalContent,
                    showCancelButton: true,
                    showDenyButton: true,
                    confirmButtonText: '<i class="ri-edit-line"></i> Edit',
                    denyButtonText: '<i class="ri-delete-bin-line"></i> Cancel Event',
                    cancelButtonText: '<i class="ri-close-line"></i> Close',
                    customClass: {
                        container: 'event-modal',
                        popup: 'event-popup',
                        confirmButton: 'btn btn-primary',
                        denyButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isDenied) {
                        // Cancel Event onayı
                        Swal.fire({
                            title: 'Cancel Event',
                            html: `
                                <form id="cancelEventForm" class="text-start">
                                    <div class="mb-3">
                                        <label class="form-label">Cancellation Reason</label>
                                        <textarea class="form-control" id="cancelReason" 
                                                  rows="3" 
                                                  placeholder="Please enter the reason for cancellation..."
                                                  required></textarea>
                                    </div>
                                </form>
                            `,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: '<i class="ri-check-line"></i> Yes, cancel it',
                            cancelButtonText: '<i class="ri-close-line"></i> No, keep it',
                            customClass: {
                                container: 'event-modal',
                                popup: 'event-popup',
                                confirmButton: 'btn btn-danger',
                                cancelButton: 'btn btn-secondary'
                            },
                            buttonsStyling: false,
                            preConfirm: () => {
                                const reason = document.getElementById('cancelReason').value;
                                if (!reason.trim()) {
                                    Swal.showValidationMessage('Please enter a reason for cancellation');
                                    return false;
                                }
                                return { reason: reason.trim() };
                            }
                        }).then((confirmResult) => {
                            if (confirmResult.isConfirmed) {
                                fetch(`/api/v1/events/${event.id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        reason: confirmResult.value.reason
                                    })
                                })
                                .then(response => response.json())
                                .then(result => {
                                    if (result.success) {
                                        // Event'i takvimden kaldır
                                        event.remove();
                                        
                                        // Başarı mesajı göster
                                        Swal.fire({
                                            title: 'Cancelled!',
                                            text: 'Event has been cancelled successfully.',
                                            icon: 'success',
                                            customClass: {
                                                container: 'event-modal',
                                                popup: 'event-popup',
                                                confirmButton: 'btn btn-success'
                                            },
                                            buttonsStyling: false
                                        }).then(() => {
                                            // Takvimi yenile
                                            if (this.calendar) {
                                                this.calendar.refetchEvents();
                                            }
                                        });
                                    } else {
                                        throw new Error(result.message || 'Failed to cancel event');
                                    }
                                })
                                .catch(error => {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: error.message || 'Something went wrong while cancelling the event',
                                        icon: 'error',
                                        customClass: {
                                            container: 'event-modal',
                                            popup: 'event-popup',
                                            confirmButton: 'btn btn-danger'
                                        },
                                        buttonsStyling: false
                                    });
                                });
                            }
                        });
                    } else if (result.isConfirmed) {
                        // Edit butonuna tıklandığında yeni bir modal aç
                        Swal.fire({
                            title: 'Edit Event',
                            html: `
                                <form id="editEventForm" class="text-start">
                                    <!-- Başlık -->
                                    <div class="mb-3">
                                        <label class="form-label">Title</label>
                                        <input type="text" class="form-control" id="editTitle" 
                                               value="${event.title}" required>
                                    </div>

                                    <!-- Tarih Seçimi -->
                                    <div class="mb-3">
                                        <label class="form-label">Date</label>
                                        <input type="date" class="form-control" id="editDate" 
                                               value="${event.start.toISOString().split('T')[0]}" required>
                                    </div>

                                    <!-- Tam Gün / Saat Seçimi -->
                                    <div class="mb-3">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="editAllDay" 
                                                   ${event.allDay ? 'checked' : ''}>
                                            <label class="form-check-label" for="editAllDay">
                                                All Day Event
                                            </label>
                                        </div>
                                        
                                        <div id="timeSelectionDiv" class="${event.allDay ? 'd-none' : ''}">
                                            <div class="row">
                                                <div class="col-6">
                                                    <label class="form-label">Start Time</label>
                                                    <input type="time" class="form-control" id="editStartTime" 
                                                           value="${event.allDay ? '09:00' : event.start.toTimeString().slice(0, 5)}">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label">End Time</label>
                                                    <input type="time" class="form-control" id="editEndTime" 
                                                           value="${event.allDay ? '17:00' : (event.end ? event.end.toTimeString().slice(0, 5) : '17:00')}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Açıklama -->
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" id="editDescription" rows="3">${event.extendedProps.description || ''}</textarea>
                                    </div>

                                    <!-- Durum -->
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" id="editStatus">
                                            <option value="scheduled" ${event.extendedProps.status === 'scheduled' ? 'selected' : ''}>Scheduled</option>
                                            <option value="completed" ${event.extendedProps.status === 'completed' ? 'selected' : ''}>Completed</option>
                                            <option value="cancelled" ${event.extendedProps.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                                        </select>
                                    </div>
                                </form>
                            `,
                            showCancelButton: true,
                            confirmButtonText: '<i class="ri-edit-line"></i> Edit',
                            denyButtonText: '<i class="ri-delete-bin-line"></i> Delete',
                            cancelButtonText: '<i class="ri-close-line"></i> Close',
                            customClass: {
                                container: 'event-modal',
                                popup: 'edit-event-popup',
                                confirmButton: 'btn btn-primary',
                                denyButton: 'btn btn-danger',
                                cancelButton: 'btn btn-secondary'
                            },
                            buttonsStyling: false,
                            didOpen: () => {
                                // All Day checkbox değiştiğinde
                                $('#editAllDay').change(function () {
                                    $('#timeSelectionDiv').toggleClass('d-none', this.checked);
                                });
                            },
                            preConfirm: () => {
                                // Form verilerini topla
                                const formData = {
                                    title: document.getElementById('editTitle').value,
                                    date: document.getElementById('editDate').value,
                                    allDay: document.getElementById('editAllDay').checked,
                                    startTime: document.getElementById('editStartTime').value,
                                    endTime: document.getElementById('editEndTime').value,
                                    description: document.getElementById('editDescription').value,
                                    status: document.getElementById('editStatus').value
                                };

                                // Validasyon
                                if (!formData.title || !formData.date) {
                                    Swal.showValidationMessage('Please fill in all required fields');
                                    return false;
                                }

                                // Saat kontrolü
                                if (!formData.allDay && formData.startTime >= formData.endTime) {
                                    Swal.showValidationMessage('End time must be after start time');
                                    return false;
                                }

                                return formData;
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // API'ye gönderilecek veriyi hazırla
                                const eventData = {
                                    id: event.id,
                                    title: result.value.title,
                                    start: result.value.allDay ?
                                        result.value.date :
                                        `${result.value.date}T${result.value.startTime}`,
                                    end: result.value.allDay ?
                                        result.value.date :
                                        `${result.value.date}T${result.value.endTime}`,
                                    allDay: result.value.allDay,
                                    description: result.value.description,
                                    status: result.value.status
                                };

                                // this bağlamını doğru şekilde kullanarak updateEvent'i çağır
                                this.updateEvent.call(this, eventData);
                            }
                        });
                    }
                });
            },
            eventDrop: async (info) => {
                try {
                    await this.updateEvent(info.event); // Call function to handle event update
                    Swal.fire({
                        title: "Success",
                        html: `<div class="text-center">Event updated successfully!</div>`,
                        icon: "success"
                    });
                } catch (error) {
                    Swal.fire({
                        title: "Error",
                        html: `<div class="text-center">Error updating event.</div>`,
                        icon: "error"
                    });
                    console.error('Error updating event:', error);
                }
            },
            eventResize: async (info) => {
                try {
                    await this.updateEvent(info.event); // Also handle resizing to change end time
                    alert('Event updated successfully!');
                } catch (error) {
                    console.error('Error updating event:', error);
                    alert('Error updating event.');
                }
            },
            titleFormat: { // Başlık formatını özelleştir
                month: 'long yyyy',
                week: "MMM d, yyyy", // 'Mar 31 - Apr 6, 2025' formatı
                day: 'long'
            },
            views: {
                timeGridWeek: {
                    titleFormat: function (date) {
                        let start = date.start.marker;
                        let end = date.end.marker;

                        // Tarihleri formatlama
                        let startStr = new Date(start).toLocaleDateString('en-US', {
                            month: 'short',
                            day: 'numeric'
                        });

                        let endStr = new Date(end).toLocaleDateString('en-US', {
                            month: 'short',
                            day: 'numeric',
                            year: 'numeric'
                        });

                        return `${startStr} - ${endStr}`;
                    }
                },
                dayGridMonth: {
                    titleFormat: { year: 'numeric', month: 'long' }
                },
                timeGridDay: {
                    titleFormat: { year: 'numeric', month: 'long', day: 'numeric' }
                }
            }
        });
        this.calendar.render();
    }

    async init() {
        try {
            this.employees = await this.fetchEmployees();
            this.initCalendar(); // Calendar'ı başlat ve instance'ı sakla
        } catch (error) {
            console.error('Failed to fetch employees or initialize the calendar:', error);
        }
    }

    popup(startDate, endDate, calendar) {
        // Mapping employee options for the select input
        let employeeOptions = this.employees.map(emp =>
            `<option value="${emp.name} ${emp.surname}" data-id="${emp.id}">${emp.name} ${emp.surname}</option>`
        ).join('');

        // Initializing the SweetAlert2 popup
        Swal.fire({
            title: "Creëer een evenement",
            html: `<div class="mb-2"><input type="text" id="title" class="form-control" placeholder="Event Title"></div>
                   <div class=""><select id="employeeId" class="form-control select2-multiple text-start" multiple size="5">${employeeOptions}</select></div>`,
            confirmButtonText: "Voeg evenement toe",
            showCancelButton: true,
            cancelButtonText: "Sluiten",
            didOpen: () => {
                $('#employeeId').select2({
                    dropdownParent: $(".swal2-modal"),
                    placeholder: "Select employees",
                    allowClear: true
                });
            },
            preConfirm: () => {
                const title = Swal.getPopup().querySelector('#title').value;
                const selectedOptions = [...Swal.getPopup().querySelector('#employeeId').selectedOptions];
                const employes = selectedOptions.map(option => option); // Getting IDs directly

                if (!title || employes.length === 0) {
                    Swal.showValidationMessage("Voer de titel in en selecteer minimaal één medewerker");
                }

                return { title: title, employes: employes, start: startDate, end: endDate };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Creating an event for each selected employee
                result.value.employes.forEach(employee => {
                    calendar.addEvent({
                        title: result.value.title + ' - ' + employee.value,
                        start: result.value.start,
                        end: result.value.end,
                        allDay: endDate === startDate + "T23:59:59", // Determines if the event is all day
                        extendedProps: {
                            employeeId: employee.getAttribute('data-id')
                        }
                    });
                    // Assuming sendRequest can handle an array of IDs
                    this.sendRequest(result.value.title, result.value.start, result.value.end, employee.getAttribute('data-id'));
                });
            }
        });
    }

    async deleteEvent(id) {
        const response = await fetch('/tenant/deleteEvent', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({ 'id': id })
        });
        console.log(response);
    }

    // Async function to handle event updates
    async updateEvent(event) {
        try {
            const response = await fetch(`/api/v1/events/${event.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    title: event.title,
                    start_date: event.start,
                    end_date: event.end,
                    description: event.description,
                    status: event.status,
                    is_all_day: event.allDay
                })
            });

            const result = await response.json();

            if (result.success) {
                // Başarılı güncelleme
                await Swal.fire({
                    title: 'Success!',
                    text: 'Event has been updated successfully',
                    icon: 'success',
                    customClass: {
                        container: 'event-modal',
                        popup: 'event-popup',
                        confirmButton: 'btn btn-success'
                    },
                    buttonsStyling: false
                });

                // Calendar'ı yenile
                if (this.calendar) {
                    this.calendar.refetchEvents();
                }
            } else {
                throw new Error(result.message || 'Failed to update event');
            }
        } catch (error) {
            await Swal.fire({
                title: 'Error!',
                text: error.message || 'Something went wrong while updating the event',
                icon: 'error',
                customClass: {
                    container: 'event-modal',
                    popup: 'event-popup',
                    confirmButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });
        }
    }

}

document.addEventListener('DOMContentLoaded', function () {
    new MyCalendar('calendar');
});
