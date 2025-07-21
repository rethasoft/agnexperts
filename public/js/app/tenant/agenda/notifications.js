/**
 * Calendar Notification Handling
 */

/**
 * Show a notification toast
 * @param {string} title Toast title
 * @param {string} message Toast message
 * @param {string} type Toast type (success, info, warning, danger)
 */
function showNotification(title, message, type = 'info') {
    // Create toast container if it doesn't exist
    const toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'position-fixed top-0 end-0 p-3';
        container.style.zIndex = 1070;
        document.body.appendChild(container);
    }

    const toastId = 'toast-' + Date.now();
    const toast = `
        <div id="${toastId}" class="toast border-0 border-start border-4 border-${type}" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">${title}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `;

    document.getElementById('toast-container').innerHTML += toast;
    const toastEl = new bootstrap.Toast(document.getElementById(toastId), {
        delay: 5000,
        autohide: true
    });
    toastEl.show();
}