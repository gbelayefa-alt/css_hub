/*
 * Name: Daniel Yu
 * Date: April 2, 2026
 * Description: Admin panel JavaScript for inline order status updates.
 */

/**
 * Initializes event listeners for the admin page once the DOM content is loaded.
 * Listens for changes on all select elements with the class 'admin-status-select' to trigger status updates.
 * @returns {void}
 */
document.addEventListener('DOMContentLoaded', () => {
    const statusSelects = document.querySelectorAll('.admin-status-select');
    statusSelects.forEach(select => {
        select.addEventListener('change', function (e) {
            const orderId = this.getAttribute('data-order-id');
            const newStatus = this.value;
            updateOrderStatus(orderId, newStatus, this);
        });
    });
});

/**
 * Sends an AJAX request to update the status of a merchandise order.
 * Disables the select while the request is in progress, then updates the status cell in the table based on the response.
 *
 * @param {string} orderId - The ID of the order to update.
 * @param {string} status - The new status to set for the order.
 * @param {HTMLSelectElement} selectElement - The select element that triggered the update, used for UI updates.
 * @returns {Promise<void>}
 */
async function updateOrderStatus(orderId, status, selectElement) {
    selectElement.disabled = true;
    try {
        const response = await fetch('../handlers/update_status_ajax.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `order_id=${encodeURIComponent(orderId)}&status=${encodeURIComponent(status)}`
        });
        const result = await response.json();
        if (result.success) {
            const row = selectElement.closest('tr');
            const statusCell = row.querySelector('.status-cell');
            if (statusCell) {
                statusCell.className = `status-cell status-${status}`;
                statusCell.textContent = status.charAt(0).toUpperCase() + status.slice(1);
            }
            showAdminMessage('Status updated.', 'success');
        } else {
            showAdminMessage('Update failed.', 'error');
            location.reload();
        }
    } catch (err) {
        showAdminMessage('Network error.', 'error');
        location.reload();
    } finally {
        selectElement.disabled = false;
    }
}

/**
 * Displays a temporary message on the admin page.
 * Creates the message container if it does not already exist.
 *
 * @param {string} msg - The message to display.
 * @param {string} type - The type of message ('success' or 'error') to determine styling.
 * @returns {void}
 */
function showAdminMessage(msg, type) {
    let msgDiv = document.getElementById('admin-message');
    if (!msgDiv) {
        msgDiv = document.createElement('div');
        msgDiv.id = 'admin-message';
        const main = document.querySelector('main');
        if (main) main.prepend(msgDiv);
    }
    msgDiv.innerHTML = `<div class="admin-msg ${type}">${msg}</div>`;
    setTimeout(() => { msgDiv.innerHTML = ''; }, 3000);
}