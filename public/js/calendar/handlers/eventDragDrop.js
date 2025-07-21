export function handleEventDragDrop(info) {
    const token = document.querySelector('meta[name="csrf-token"]').content;
    
    // Tarihleri doğru formata çevir
    const startDate = formatDateForDatabase(info.event.start);
    const endDate = info.event.end ? formatDateForDatabase(info.event.end) : null;

    Swal.fire({
        title: 'Event tarihini değiştirmek istiyor musunuz?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Evet',
        cancelButtonText: 'Hayır'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/api/v1/events/${info.event.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    start_date: startDate,
                    end_date: endDate,
                    is_all_day: info.event.allDay,
                    process: 'dragAndDrop'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    info.revert();
                    Swal.fire('Hata!', 'Tarih güncellenemedi.', 'error');
                } else {
                    Swal.fire('Başarılı!', 'Tarih güncellendi.', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                info.revert();
                Swal.fire('Hata!', 'Bir hata oluştu.', 'error');
            });
        } else {
            info.revert();
        }
    });
}

// Tarih formatı için yardımcı fonksiyon
function formatDateForDatabase(date) {
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