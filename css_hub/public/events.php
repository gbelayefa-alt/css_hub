<?php
/*
 * Name: Amanda Gbe and Daniel Yu
 * Date: April 19, 2026
 * Description: Events page – list view, calendar view, filtering, AJAX register/unregister.
 */
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}
$email = $_SESSION['email'];

$events_stmt = $pdo->query("
    SELECT event_id, event_title, event_description, event_date, event_time, location, stream_link
    FROM events
    ORDER BY event_date DESC, event_time DESC
");
$all_events = $events_stmt->fetchAll(PDO::FETCH_ASSOC);

$registered_stmt = $pdo->prepare("
    SELECT e.event_id, e.event_title, e.event_date, e.event_time, e.location
    FROM event_registrations er
    JOIN events e ON er.event_id = e.event_id
    WHERE er.email = :email
    ORDER BY e.event_date DESC, e.event_time DESC
");
$registered_stmt->execute(['email' => $email]);
$registered_events = $registered_stmt->fetchAll(PDO::FETCH_ASSOC);
$registered_ids = array_column($registered_events, 'event_id');

require_once '../includes/header.php';
?>

<h1>Events</h1>
<p class="welcome-message">Hello, <?php echo htmlspecialchars($_SESSION['first_name']); ?></p>
<div id="event-message"></div>

<!-- Horizontal filter bar with extra bottom margin (32px) -->
<div class="filter-bar" style="display: flex; gap: 16px; align-items: center; flex-wrap: nowrap; overflow-x: auto; margin-bottom: 32px;">
    <input type="text" id="filterTitle" placeholder="Filter by title..." class="filter-input" style="flex: 1; min-width: 120px;">
    <input type="text" id="filterLocation" placeholder="Filter by location..." class="filter-input" style="flex: 1; min-width: 120px;">
    <input type="date" id="filterDate" placeholder="Filter by date" class="filter-input" style="flex: 1; min-width: 120px;">
    <button id="clearFilters" class="filter-btn" style="white-space: nowrap;">Clear Filters</button>
</div>

<div class="view-toggle">
    <button id="listViewBtn" class="view-btn active">List View</button>
    <button id="calendarViewBtn" class="view-btn">Calendar View</button>
</div>

<div id="listViewContainer">
    <h2>Available Events</h2>
    <div id="eventsList">
        <?php foreach ($all_events as $event): ?>
            <div class="event-card" data-title="<?= strtolower($event['event_title']) ?>" data-location="<?= strtolower($event['location']) ?>" data-date="<?= $event['event_date'] ?>">
                <h3><?= htmlspecialchars($event['event_title']) ?></h3>
                <p><?= htmlspecialchars($event['event_description']) ?></p>
                <p><strong>Date:</strong> <?= $event['event_date'] ?> at <?= $event['event_time'] ?></p>
                <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
                <?php if (!empty($event['stream_link'])): ?>
                    <p><a href="<?= $event['stream_link'] ?>" target="_blank">Watch Stream →</a></p>
                <?php endif; ?>
                <?php if (in_array($event['event_id'], $registered_ids)): ?>
                    <button type="button" class="unregister" data-event-id="<?= $event['event_id'] ?>">Unregister</button>
                <?php else: ?>
                    <button type="button" class="register" data-event-id="<?= $event['event_id'] ?>">Register</button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div id="calendarViewContainer" style="display: none;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <button id="prevMonthBtn" style="background: #6c63ff; color: white; border: none; padding: 8px 16px; border-radius: 20px; cursor: pointer;">◀ Previous</button>
        <h2 id="calendarMonthYear"></h2>
        <button id="nextMonthBtn" style="background: #6c63ff; color: white; border: none; padding: 8px 16px; border-radius: 20px; cursor: pointer;">Next ▶</button>
    </div>
    <div id="calendar" style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; background: white; padding: 16px; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);"></div>
</div>

<h2>My Registered Events</h2>
<div id="registered-events-container">
    <table class="simple-table" id="registered-events-table" <?php if (count($registered_events) == 0) echo 'style="display:none;"'; ?>>
        <thead>
            <tr>
                <th>Event Title</th>
                <th>Date</th>
                <th>Time</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody id="registered-events-body">
            <?php foreach ($registered_events as $registered): ?>
                <tr data-event-id="<?= $registered['event_id'] ?>">
                    <td><?= htmlspecialchars($registered['event_title']) ?></td>
                    <td><?= $registered['event_date'] ?></td>
                    <td><?= $registered['event_time'] ?></td>
                    <td><?= htmlspecialchars($registered['location']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (count($registered_events) == 0): ?>
        <p id="no-registered-events">You have not registered for any events yet.</p>
    <?php endif; ?>
</div>

<div id="unregisterModal" class="modal" style="display:none;">
    <div class="modal-content">
        <h3>Unregister from Event</h3>
        <p>Are you sure you want to unregister from this event?</p>
        <input type="hidden" id="modal-event-id">
        <div class="modal-actions">
            <button onclick="closeModal()">Cancel</button>
            <button onclick="confirmUnregister()">Confirm</button>
        </div>
    </div>
</div>

<script src="../js/main.js"></script>
<script>
    // All events data from PHP
    const allEvents = <?php echo json_encode($all_events); ?>;
    let currentCalendarYear = new Date().getFullYear();
    let currentCalendarMonth = new Date().getMonth();

    /**
     * Returns the number of days in a given month.
     * @param {number} year - The year.
     * @param {number} month - The month (0-11).
     * @returns {number} Number of days in the month.
     */
    function daysInMonth(year, month) {
        return new Date(year, month + 1, 0).getDate();
    }

    /**
     * Returns the day of the week for the first day of a month.
     * @param {number} year - The year.
     * @param {number} month - The month (0-11).
     * @returns {number} Day of week (0 = Sunday, 6 = Saturday).
     */
    function firstDayOfMonth(year, month) {
        return new Date(year, month, 1).getDay();
    }

    /**
     * Gets the current filter values from the input fields.
     * @returns {Object} An object with title, location, and date filter strings.
     */
    function getFilterValues() {
        return {
            title: (document.getElementById('filterTitle')?.value || '').toLowerCase(),
            location: (document.getElementById('filterLocation')?.value || '').toLowerCase(),
            date: document.getElementById('filterDate')?.value || ''
        };
    }

    /**
     * Filters an array of events based on the given filter criteria.
     * @param {Array} events - The array of event objects.
     * @param {Object} filters - The filter criteria (title, location, date).
     * @returns {Array} Filtered events.
     */
    function filterEvents(events, filters) {
        return events.filter(ev => {
            if (filters.title && !ev.event_title.toLowerCase().includes(filters.title)) return false;
            if (filters.location && !ev.location.toLowerCase().includes(filters.location)) return false;
            if (filters.date && ev.event_date !== filters.date) return false;
            return true;
        });
    }

    /**
     * Generates and displays the calendar grid based on current month/year and filters.
     * @returns {void}
     */
    function generateCalendar() {
        const filters = getFilterValues();
        const filtered = filterEvents(allEvents, filters);
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        document.getElementById('calendarMonthYear').innerText = `${monthNames[currentCalendarMonth]} ${currentCalendarYear}`;
        const daysCount = daysInMonth(currentCalendarYear, currentCalendarMonth);
        const startDay = firstDayOfMonth(currentCalendarYear, currentCalendarMonth);
        const today = new Date();
        const isCurrent = (today.getFullYear() === currentCalendarYear && today.getMonth() === currentCalendarMonth);
        let html = '';
        const weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        weekdays.forEach(day => html += `<div style="text-align:center; font-weight:600; padding:8px; background:#6c63ff; color:white; border-radius:8px;">${day}</div>`);
        for (let i = 0; i < startDay; i++) html += `<div style="background:#f9f9f9; border:none; min-height:80px;"></div>`;
        for (let d = 1; d <= daysCount; d++) {
            const dateStr = `${currentCalendarYear}-${String(currentCalendarMonth+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
            let dayEvents = filtered.filter(ev => ev.event_date === dateStr);
            let isToday = isCurrent && (today.getDate() === d);
            let bg = isToday ? 'background:#f0e6ff; border:1px solid #6c63ff;' : 'background:white; border:1px solid #eee;';
            html += `<div style="${bg} border-radius:8px; padding:6px; min-height:80px;">
                        <div style="font-weight:600;">${d}</div>`;
            dayEvents.forEach(ev => {
                html += `<div style="font-size:10px; background:#4d9fff; color:white; padding:2px 4px; border-radius:4px; margin-top:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="${ev.event_title}">${ev.event_title.substring(0,12)}</div>`;
            });
            html += `</div>`;
        }
        document.getElementById('calendar').innerHTML = html;
    }

    /**
     * Refreshes the calendar display if the calendar view is currently visible.
     * @returns {void}
     */
    function refreshCalendarIfVisible() {
        if (document.getElementById('calendarViewContainer').style.display !== 'none') {
            generateCalendar();
        }
    }
    window.refreshCalendarIfVisible = refreshCalendarIfVisible;

    // Previous month button
    document.getElementById('prevMonthBtn').addEventListener('click', () => {
        currentCalendarMonth--;
        if (currentCalendarMonth < 0) {
            currentCalendarMonth = 11;
            currentCalendarYear--;
        }
        generateCalendar();
    });

    // Next month button
    document.getElementById('nextMonthBtn').addEventListener('click', () => {
        currentCalendarMonth++;
        if (currentCalendarMonth > 11) {
            currentCalendarMonth = 0;
            currentCalendarYear++;
        }
        generateCalendar();
    });

    // Filter elements
    const filterTitle = document.getElementById('filterTitle');
    const filterLocation = document.getElementById('filterLocation');
    const filterDate = document.getElementById('filterDate');
    const clearBtn = document.getElementById('clearFilters');
    const eventCards = document.querySelectorAll('#eventsList .event-card');

    /**
     * Applies filters to both the list view and the calendar view.
     * @returns {void}
     */
    function applyFiltersAndUpdate() {
        const filters = getFilterValues();
        eventCards.forEach(card => {
            const title = card.getAttribute('data-title');
            const location = card.getAttribute('data-location');
            const date = card.getAttribute('data-date');
            let show = true;
            if (filters.title && !title.includes(filters.title)) show = false;
            if (filters.location && !location.includes(filters.location)) show = false;
            if (filters.date && date !== filters.date) show = false;
            card.style.display = show ? 'block' : 'none';
        });
        refreshCalendarIfVisible();
    }

    filterTitle.addEventListener('input', applyFiltersAndUpdate);
    filterLocation.addEventListener('input', applyFiltersAndUpdate);
    filterDate.addEventListener('change', applyFiltersAndUpdate);
    clearBtn.addEventListener('click', () => {
        filterTitle.value = '';
        filterLocation.value = '';
        filterDate.value = '';
        applyFiltersAndUpdate();
    });

    // View toggle buttons
    const listViewBtn = document.getElementById('listViewBtn');
    const calendarViewBtn = document.getElementById('calendarViewBtn');
    const listContainer = document.getElementById('listViewContainer');
    const calendarContainer = document.getElementById('calendarViewContainer');

    listViewBtn.addEventListener('click', () => {
        listContainer.style.display = 'block';
        calendarContainer.style.display = 'none';
        listViewBtn.classList.add('active');
        calendarViewBtn.classList.remove('active');
    });

    calendarViewBtn.addEventListener('click', () => {
        listContainer.style.display = 'none';
        calendarContainer.style.display = 'block';
        calendarViewBtn.classList.add('active');
        listViewBtn.classList.remove('active');
        generateCalendar();
    });

    /**
     * Attaches event listeners to register/unregister buttons.
     * @returns {void}
     */
    function attachEventHandlers() {
        document.querySelectorAll('.register').forEach(btn => {
            if (!btn.hasAttribute('data-listener')) {
                btn.setAttribute('data-listener', 'true');
                btn.addEventListener('click', function() {
                    registerEventAjax(this.dataset.eventId, this);
                });
            }
        });
        document.querySelectorAll('.unregister').forEach(btn => {
            if (!btn.hasAttribute('data-listener')) {
                btn.setAttribute('data-listener', 'true');
                btn.addEventListener('click', function() {
                    openUnregisterModal(this.dataset.eventId, this);
                });
            }
        });
    }

    attachEventHandlers();
    const observer = new MutationObserver(() => attachEventHandlers());
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
</script>

<?php require_once '../includes/footer.php'; ?>