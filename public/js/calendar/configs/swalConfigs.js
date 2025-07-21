// Swal konfigürasyonları
export const SwalConfigs = {
    edit: {
        title: 'Afspraak bewerken',
        showCancelButton: true,
        confirmButtonText: 'Bijwerken',
        cancelButtonText: 'Annuleren',
        customClass: {
            container: 'event-popup',
            popup: 'event-popup-content',
            header: 'event-popup-header',
            title: 'event-popup-title',
            closeButton: 'event-popup-close',
            content: 'event-popup-body',
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-secondary',
            actions: 'event-popup-actions'
        },
        width: '600px',
        showClass: {
            popup: 'animate__animated animate__fadeInDown animate__faster'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp animate__faster'
        }
    },
    
    create: {
        title: 'Nieuwe afspraak toevoegen',
        showCancelButton: true,
        confirmButtonText: 'Toevoegen',
        cancelButtonText: 'Annuleren',
        customClass: {
            container: 'event-popup',
            popup: 'event-popup-content',
            header: 'event-popup-header',
            title: 'event-popup-title',
            closeButton: 'event-popup-close',
            content: 'event-popup-body',
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-secondary',
            actions: 'event-popup-actions'
        },
        width: '600px',
        showClass: {
            popup: 'animate__animated animate__fadeInDown animate__faster'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp animate__faster'
        }
    },
    
    delete: {
        title: 'Weet je zeker dat je dit wilt verwijderen?',
        text: "Deze actie kan niet ongedaan worden gemaakt!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ja, verwijderen!',
        cancelButtonText: 'Annuleren',
        customClass: {
            container: 'event-popup delete-popup',
            popup: 'event-popup-content',
            header: 'event-popup-header',
            title: 'event-popup-title text-danger',
            confirmButton: 'btn btn-danger',
            cancelButton: 'btn btn-secondary',
            actions: 'event-popup-actions'
        },
        showClass: {
            popup: 'animate__animated animate__zoomIn animate__faster'
        },
        hideClass: {
            popup: 'animate__animated animate__zoomOut animate__faster'
        }
    }
};

// Stil tanımlamaları
const style = document.createElement('style');
style.textContent = `
    /* Mevcut stiller aynı kalacak */
    ${document.querySelector('style')?.textContent || ''}

    /* Yeni stiller ekleyelim */
    .event-popup {
        --swal2-popup-width: 600px;
    }

    .event-popup .swal2-html-container {
        margin: 0;
        padding: 20px;
    }

    .event-popup .swal2-title {
        padding: 1rem;
        margin: 0;
        border-bottom: 1px solid #eee;
    }

    .event-popup .swal2-actions {
        margin-top: 0;
        padding: 1rem;
        border-top: 1px solid #eee;
    }

    .event-popup .btn {
        padding: 8px 24px;
        font-weight: 500;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .event-popup .event-popup-content {
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .event-popup .event-popup-header {
        border-bottom: 1px solid #eee;
        padding: 1rem;
    }

    .event-popup .event-popup-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #333;
    }

    .event-popup .event-popup-body {
        padding: 1.5rem;
    }

    .event-popup .event-popup-actions {
        border-top: 1px solid #eee;
        padding: 1rem;
    }

    .event-popup .form-control {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .event-popup .form-control:focus {
        border-color: #4a90e2;
        box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
    }

    .event-popup .form-label {
        font-weight: 500;
        color: #555;
    }

    .event-popup .event-info {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
    }

    .event-popup .event-info .mb-2 {
        display: flex;
        align-items: center;
        padding: 0.5rem 0;
    }

    .event-popup .event-info i {
        width: 24px;
        text-align: center;
        margin-right: 10px;
    }

    .event-popup .badge {
        padding: 0.4em 0.8em;
        font-size: 0.85em;
        border-radius: 6px;
    }

    .delete-popup .event-popup-content {
        border: 2px solid #dc3545;
    }

    .event-popup .list-unstyled li {
        padding: 0.4rem 0;
        display: flex;
        align-items: center;
    }

    .event-popup .list-unstyled i {
        margin-right: 8px;
    }

    /* Animasyon için gerekli CSS */
    @import 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css';

    /* Responsive tasarım */
    @media (max-width: 576px) {
        .event-popup-content {
            width: 95% !important;
            margin: 0 auto;
        }

        .event-popup .event-popup-body {
            padding: 1rem;
        }

        .event-popup .row {
            flex-direction: column;
        }

        .event-popup .col-md-6 {
            width: 100%;
            margin-bottom: 1rem;
        }
    }
`;

// Stil eklemeden önce varsa eski stili kaldır
const existingStyle = document.querySelector('style[data-event-popup-styles]');
if (existingStyle) {
    existingStyle.remove();
}

// Yeni stili ekle
style.setAttribute('data-event-popup-styles', '');
document.head.appendChild(style); 