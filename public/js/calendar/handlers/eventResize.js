export function handleEventResize(info) {
    const token = document.querySelector('meta[name="csrf-token"]').content;

    Swal.fire({
        title: 'Wilt u de duur van dit evenement wijzigen?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ja',
        cancelButtonText: 'Nee',
        // Improve UI responsiveness with faster animation
        showClass: {
            popup: 'animate__animated animate__fadeIn animate__faster'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOut animate__faster'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Show a lightweight indicator that work is happening
            const loadingToast = document.createElement('div');
            loadingToast.className = 'position-fixed top-0 end-0 p-3';
            loadingToast.style.zIndex = '1070';
            loadingToast.innerHTML = `
                <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong class="me-auto">Bijwerken...</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        Event wordt bijgewerkt
                    </div>
                </div>
            `;
            document.body.appendChild(loadingToast);
            
            fetch(`/api/v1/events/${info.event.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    // Add cache control headers for better performance
                    'Cache-Control': 'no-cache'
                },
                body: JSON.stringify({
                    start_date: formatDateForBackend(info.event.start),
                    end_date: info.event.end ? formatDateForBackend(info.event.end) : null,
                    employee_id: info.event.extendedProps?.employee_id,
                    process: 'resize'
                })
            })
            .then(response => response.json())
            .then(data => {
                // Remove the loading indicator
                document.body.removeChild(loadingToast);
                
                if (!data.success) {
                    info.revert();
                    Swal.fire({
                        title: 'Fout!',
                        text: data.message || 'Duur kon niet worden bijgewerkt.',
                        icon: 'error',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        title: 'Gelukt!',
                        text: 'Duur is succesvol bijgewerkt.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            })
            .catch(error => {
                // Remove the loading indicator
                document.body.removeChild(loadingToast);
                
                console.error('Error:', error);
                info.revert();
                Swal.fire({
                    title: 'Fout!',
                    text: 'Er is een fout opgetreden.',
                    icon: 'error',
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        } else {
            info.revert();
        }
    });
}

function formatDateForBackend(date) {
    if (!date) return null;
    
    const d = new Date(date);
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    const hours = String(d.getHours()).padStart(2, '0');
    const minutes = String(d.getMinutes()).padStart(2, '0');
    const seconds = '00';
    
    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}