/*
 * Authors: Daniel Yu and Amanda Gbe
 * Date: April 2, 2026
 * Description: Global JavaScript utilities for CSS Web Hub.
 */

document.addEventListener('DOMContentLoaded', () => {

    /**
     * Displays a temporary flash message (success/error) in the specified element.
     *
     * @param {string} elementId - The ID of the element where the message should appear.
     * @param {string} message - The message text to display.
     * @param {string} type - The type of message ('success' or 'error'), which determines styling.
     * @returns {void}
     */
    window.showFlashMessage = function (elementId, message, type = 'success') {
        const el = document.getElementById(elementId);
        if (el) {
            el.innerHTML = `<div class="flash-${type}">${message}</div>`;
            setTimeout(() => { el.innerHTML = ''; }, 3000);
        }
    };

    /**
     * Registers a user for an event via AJAX and updates the UI.
     *
     * @param {number} eventId - The ID of the event to register for.
     * @param {HTMLElement} buttonEl - The button element that was clicked (used for UI updates).
     * @returns {Promise<void>}
     */
    window.registerEventAjax = async function (eventId, buttonEl) {
        const messageBox = document.getElementById('event-message');
        if (!eventId) return;

        try {
            const response = await fetch('../ajax/register_event.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'event_id=' + encodeURIComponent(eventId)
            });
            const data = await response.json();

            if (data.success) {
                if (messageBox) {
                    messageBox.innerHTML = `<div class="flash-success">${data.message}</div>`;
                    setTimeout(() => { messageBox.innerHTML = ''; }, 3000);
                }
                buttonEl.textContent = 'Unregister';
                buttonEl.classList.remove('register');
                buttonEl.classList.add('unregister');
                buttonEl.dataset.eventId = eventId;
                const newBtn = buttonEl.cloneNode(true);
                buttonEl.parentNode.replaceChild(newBtn, buttonEl);
                newBtn.addEventListener('click', function (e) {
                    openUnregisterModal(eventId, newBtn);
                });

                if (data.row_html) {
                    const tbody = document.getElementById('registered-events-body');
                    const emptyMsg = document.getElementById('no-registered-events');
                    const table = document.getElementById('registered-events-table');
                    if (emptyMsg) emptyMsg.remove();
                    if (table && table.style.display === 'none') {
                        table.style.display = 'table';
                    }
                    if (tbody) {
                        tbody.insertAdjacentHTML('afterbegin', data.row_html);
                    }
                }
                if (typeof window.refreshCalendarIfVisible === 'function') {
                    window.refreshCalendarIfVisible();
                }
            } else {
                if (messageBox) {
                    messageBox.innerHTML = `<div class="flash-error">${data.message}</div>`;
                    setTimeout(() => { messageBox.innerHTML = ''; }, 3000);
                }
            }
        } catch (error) {
            console.error('Event registration failed:', error);
            if (messageBox) {
                messageBox.innerHTML = `<div class="flash-error">Something went wrong. Please try again.</div>`;
                setTimeout(() => { messageBox.innerHTML = ''; }, 3000);
            }
        }
    };

    // ----- Unregister Modal -----
    let currentUnregisterButton = null;
    let currentUnregisterEventId = null;

    /**
     * Opens the confirmation modal for unregistering from an event.
     *
     * @param {number} eventId - The ID of the event to unregister from.
     * @param {HTMLElement} buttonEl - The button element that was clicked (used for later UI update).
     * @returns {void}
     */
    window.openUnregisterModal = function (eventId, buttonEl) {
        currentUnregisterEventId = eventId;
        currentUnregisterButton = buttonEl;
        document.getElementById('modal-event-id').value = eventId;
        document.getElementById('unregisterModal').style.display = 'block';
    };

    /**
     * Closes the unregister confirmation modal.
     *
     * @returns {void}
     */
    window.closeModal = function () {
        document.getElementById('unregisterModal').style.display = 'none';
        currentUnregisterButton = null;
        currentUnregisterEventId = null;
    };

    /**
     * Confirms unregistration, sends AJAX request to delete the registration, and updates the UI.
     *
     * @returns {Promise<void>}
     */
    window.confirmUnregister = async function () {
        const eventId = currentUnregisterEventId;
        const buttonEl = currentUnregisterButton;
        if (!eventId) return;

        try {
            const response = await fetch('../ajax/unregister_event.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'event_id=' + encodeURIComponent(eventId)
            });
            const data = await response.json();

            if (data.success) {
                if (buttonEl) {
                    buttonEl.textContent = 'Register';
                    buttonEl.classList.remove('unregister');
                    buttonEl.classList.add('register');
                    const newBtn = buttonEl.cloneNode(true);
                    buttonEl.parentNode.replaceChild(newBtn, buttonEl);
                    newBtn.addEventListener('click', function (e) {
                        registerEventAjax(eventId, newBtn);
                    });
                }
                const row = document.querySelector(`#registered-events-body tr[data-event-id="${eventId}"]`);
                if (row) row.remove();
                const tbody = document.getElementById('registered-events-body');
                const table = document.getElementById('registered-events-table');
                if (tbody && tbody.children.length === 0) {
                    if (table) table.style.display = 'none';
                    const container = document.getElementById('registered-events-container');
                    if (container && !document.getElementById('no-registered-events')) {
                        container.insertAdjacentHTML('beforeend', '<p id="no-registered-events">You have not registered for any events yet.</p>');
                    }
                }
                showFlashMessage('event-message', 'Unregistered successfully', 'success');
                if (typeof window.refreshCalendarIfVisible === 'function') {
                    window.refreshCalendarIfVisible();
                }
            } else {
                showFlashMessage('event-message', data.message || 'Unregistration failed', 'error');
            }
        } catch (error) {
            console.error('Unregistration failed:', error);
            showFlashMessage('event-message', 'Network error. Please try again.', 'error');
        }
        closeModal();
    };

    // ----- Admin Dropdown -----
    const adminToggle = document.getElementById('adminToggle');
    const adminMenu = document.getElementById('adminMenu');
    if (adminToggle && adminMenu) {
        adminToggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            adminMenu.classList.toggle('show');
        });
        document.addEventListener('click', function (e) {
            if (!adminToggle.contains(e.target) && !adminMenu.contains(e.target)) {
                adminMenu.classList.remove('show');
            }
        });
    }

    // ----- Forms Dropdown -----
    const formsToggle = document.getElementById('formsToggle');
    const formsMenu = document.getElementById('formsMenu');
    if (formsToggle && formsMenu) {
        formsToggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            formsMenu.classList.toggle('show');
        });
        document.addEventListener('click', function (e) {
            if (!formsToggle.contains(e.target) && !formsMenu.contains(e.target)) {
                formsMenu.classList.remove('show');
            }
        });
    }

    // ----- Hamburger Menu -----
    const hamburgerToggle = document.getElementById('hamburgerToggle');
    const hamburgerDropdown = document.getElementById('hamburgerDropdown');
    if (hamburgerToggle && hamburgerDropdown) {
        hamburgerToggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            hamburgerDropdown.classList.toggle('show');
            // Close other dropdowns
            if (adminMenu) adminMenu.classList.remove('show');
            if (formsMenu) formsMenu.classList.remove('show');
        });
        document.addEventListener('click', function (e) {
            if (!hamburgerToggle.contains(e.target) && !hamburgerDropdown.contains(e.target)) {
                hamburgerDropdown.classList.remove('show');
            }
        });
    }

    // ----- Delete Suggestion Modal (for admin_suggestions.php) -----
    let currentDeleteForm = null;

    /**
     * Opens the delete confirmation modal for a suggestion.
     *
     * @param {HTMLFormElement} form - The form element to be submitted if the admin confirms deletion.
     * @returns {void}
     */
    window.openDeleteModal = function (form) {
        currentDeleteForm = form;
        document.getElementById('deleteModal').style.display = 'block';
    };

    /**
     * Closes the delete confirmation modal.
     *
     * @returns {void}
     */
    window.closeDeleteModal = function () {
        document.getElementById('deleteModal').style.display = 'none';
        currentDeleteForm = null;
    };

    /**
     * Confirms deletion by submitting the stored form.
     *
     * @returns {void}
     */
    window.confirmDelete = function () {
        if (currentDeleteForm) currentDeleteForm.submit();
        closeDeleteModal();
    };
});