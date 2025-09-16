<link rel="stylesheet" href="{{ asset('css/tables.css') }}">
</head>
<body onload="updateTable()">
<div class="detail-holder">
    <div id="today" class="today-date"></div>
    <div class="select">
        <div>
            <label for="view">
                <p>Select View:</p>
            </label>
            <select class="form-select" id="view" onchange="updateTable()">
                <option value="day" selected>Day</option>
                <option value="week">Week</option>
                <option value="month">Month</option>
            </select>
        </div>
        <div>
            <label for="location">
                <p>Select Lantai:</p>
            </label>
            <select class="form-select" id="location" onchange="updateTable()">
                <option value="all" selected>Semua Lantai</option>
                <option value="1">Lantai 1</option>
                <option value="2">Lantai 2</option>
            </select>
        </div>
        <button id="prev" onclick="prevDate()">Prev</button>
        <button id="next" onclick="nextDate()">Next</button>
    </div>

    <input type="hidden" id="current-date-input" value="" />
</div>


<table id="reservationTable">
    <thead>
        <tr id="headerRow">
            <!-- Headers will be populated dynamically -->
        </tr>
    </thead>
    <tbody id="tableBody">
        <!-- Table body will be populated dynamically -->
    </tbody>
</table>

<div id="eventModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h1 id="modalEventTitle"></h1>
        <p><strong>Room:</strong> <span id="modalEventRoom"></span></p>
        <p><strong>Pemesan:</strong> <span id="modalEventName"></span></p>
        <p><strong>Role:</strong> <span id="modalEventRole"></span></p>
        <p><strong>Date:</strong> <span id="modalEventDate"></span></p>
        <p><strong>Start Time:</strong> <span id="modalEventStartTime"></span></p>
        <p><strong>End Time:</strong> <span id="modalEventEndTime"></span></p>
        <p><strong>Description:</strong> <span id="modalEventDescription"></span></p>
        <p><strong>Status:</strong> <span id="modalEventStatus"></span></p>
    </div>
</div>

<script src="{{ asset('js/tables.js') }}"></script>

<script>
    let currentDate = new Date();
    let selectedView = 'day';

    document.addEventListener("DOMContentLoaded", function() {
        fetchEvents();  // Initialize event fetching and table update
        updateTodayDate();  // Initialize the today date
        document.getElementById("view").addEventListener("change", function() {
            selectedView = this.value;
            updateTable(eventsData);  // Call updateTable with eventsData on view change
        });
    });

    function getDisplayedDate() {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        const formattedDate = currentDate.toLocaleDateString('en-US', options);
        return formattedDate;
    }

    function updateTodayDate() {
        document.getElementById('today').textContent = getDisplayedDate();
        document.getElementById('current-date-input').value = currentDate.toLocaleDateString();
    }

function prevDate() {
    const currentDateInput = document.getElementById('current-date-input').value;
    currentDate = new Date(currentDateInput);
    if (selectedView === 'day') {
        currentDate.setDate(currentDate.getDate() - 1);
    } else if (selectedView === 'week') {
        currentDate.setDate(currentDate.getDate() - 7); // Update by 7 days
    } else if (selectedView === 'month') {
        currentDate.setMonth(currentDate.getMonth() - 1);
        currentDate.setDate(1); // Set the date to the first day of the month
    }
    updateTodayDate();
    updateTable(eventsData); // Call updateTable with eventsData
    
    // Update the calendar
    const calendarDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    currYear = calendarDate.getFullYear();
    currMonth = calendarDate.getMonth();
    renderCalendar(calendarDate); // Call renderCalendar with the updated date
}

function nextDate() {
    const currentDateInput = document.getElementById('current-date-input').value;
    currentDate = new Date(currentDateInput);
    if (selectedView === 'day') {
        currentDate.setDate(currentDate.getDate() + 1);
    } else if (selectedView === 'week') {
        currentDate.setDate(currentDate.getDate() + 7); // Update by 7 days
    } else if (selectedView === 'month') {
        currentDate.setMonth(currentDate.getMonth() + 1);
        currentDate.setDate(1); // Set the date to the first day of the month
    }
    updateTodayDate();
    updateTable(eventsData); // Call updateTable with eventsData
    
    // Update the calendar
    const calendarDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    currYear = calendarDate.getFullYear();
    currMonth = calendarDate.getMonth();
    renderCalendar(calendarDate); // Call renderCalendar with the updated date
}

    document.getElementById("view").addEventListener("change", function() {
        selectedView = this.value;
        updateTable(eventsData); // Call updateTable with eventsData
    });

    updateTodayDate(); // Initialize the today date

    console.log("Fetched Events:", eventsData);
</script>