let eventsData = [];

function initializeListeners() {
    document.getElementById("location").addEventListener("change", function () {
        updateTable(eventsData); // Re-render table on location change
    });

    document.getElementById("view").addEventListener("change", function () {
        updateTable(eventsData); // Re-render table on view change
    });
}

document.addEventListener("DOMContentLoaded", function () {
    fetchRooms().then(() => {
        fetchEvents().then(events => {
            eventsData = events;
            updateTable(eventsData);
        });
    });

    initializeListeners(); // Set up listeners
});


function fetchEvents() {
    return fetch('/api/events')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Fetched Events:", data);
            if (!Array.isArray(data)) {
                console.error("Fetched data is not an array:", data);
                return [];
            }
            return data;
        })
        .catch(error => {
            console.error("Error fetching events:", error);
            return []; 
        });
}

fetchEvents().then(events => {
    console.log("Fetched Events:", events);
    eventsData = events;
    updateTable(eventsData); 
});


document.getElementById("view").addEventListener("change", function() {
    updateTable(eventsData);
});

let roomData = []; 
let roomNames = [];

function fetchRooms() {
    return fetch('/api/rooms')
        .then(response => response.json())
        .then(data => {
            roomData = data; 
            roomNames = roomData.map(room => room.name); 
            console.log("Fetched Rooms:", roomData);
        })
        .catch(error => console.error("Error fetching rooms:", error));
}


fetchRooms().then(() => {
    
    fetchEvents().then(events => {
        updateTable(events);
    });
});


function updateTable(events) {
    console.log("updateTable function called");
    const currentDateInput = document.getElementById('current-date-input');
    let currentDate = new Date(currentDateInput.value);
    currentDate.setDate(currentDate.getDate() + 1); 
    currentDate.setHours(0, 0, 0, 0);
    const currentDateString = currentDate.toISOString().split('T')[0];

    if (!Array.isArray(events)) {
        console.error("Events data is not an array:", events);
        return;
    }

    events = events.filter(event => event.status === 'accepted');

    const locationDropdown = document.getElementById("location");
    const selectedLocation = locationDropdown ? locationDropdown.value : "all";

    // Filter room names by selected location
    if (selectedLocation === "all") {
        roomNames = roomData.map(room => room.name); // Show all rooms
    } else {
        roomNames = roomData
            .filter(room => room.location == selectedLocation)
            .map(room => room.name);
    }

    console.log("Filtered Rooms:", roomNames);

    var viewDropdown = document.getElementById("view");
    var selectedView = viewDropdown ? viewDropdown.value : "day";

    var headerRow = document.getElementById("headerRow");
    var tableBody = document.getElementById("tableBody");

    if (!headerRow || !tableBody) {
        console.error("Table elements not found.");
        return;
    }

    function timeToMinutes(time) {
        if (!time) return 0;
        var parts = time.split(":");
        return parseInt(parts[0]) * 60 + parseInt(parts[1]);
    }

    function createTimeSlots() {
        var slots = [];
        for (var hour = 8; hour <= 18; hour++) {
            slots.push((hour < 10 ? '0' : '') + hour + ":00");
            if (hour < 18) {
                slots.push((hour < 10 ? '0' : '') + hour + ":30");
            }
        }
        return slots;
    }

    headerRow.innerHTML = "";
    tableBody.innerHTML = "";

    if (selectedView === "day") {
        headerRow.insertAdjacentHTML("beforeend", "<th>Time</th>");
        roomNames.forEach(roomName => {
            headerRow.insertAdjacentHTML("beforeend", `<th>${roomName}</th>`);
        });

        const slots = createTimeSlots();

        slots.forEach(slot => {
            let row = `<tr><td>${slot}</td>`;

            roomNames.forEach(roomName => {
                let cell = `<td onclick="openPopup('${roomName}', '${slot}', '${currentDateString}')"></td>`;

                events.forEach(event => {
                    if (event && event.room === roomName && event.booking_date === currentDateString) {
                        const eventStart = timeToMinutes(event.start);
                        const eventEnd = timeToMinutes(event.end) + 30; 
                        const slotTime = timeToMinutes(slot);

                        if (slotTime >= eventStart && slotTime < eventEnd) {
                            if (slotTime === eventStart) {
                                const rowSpan = Math.ceil((eventEnd - eventStart) / 30);

                                cell = `<td rowspan="${rowSpan}" class="event ${event.cssClass || ''}" onclick="showEventDetails(${parseInt(event.id)}, '${event.name}', '${event.role}')">${event.title || ''}</td>`;
                            } else {
                                cell = "";
                            }
                        }
                    }
                });

                row += cell;
            });

            row += "</tr>";
            tableBody.insertAdjacentHTML("beforeend", row);
        });
    }else if (selectedView === "week") {
        // Generate 7 days starting from the current date
        const days = [];
        for (let i = 0; i < 7; i++) {
            const day = new Date(currentDate);
            day.setDate(day.getDate() + i);
            days.push(day);
        }
    
        // Create table headers
        headerRow.insertAdjacentHTML("beforeend", "<th>Room</th>");
        days.forEach(day => {
            // Extract the day of the week (e.g., "Mon", "Tue")
            const dayName = day.toLocaleDateString('en-US', { weekday: 'short' });
            headerRow.insertAdjacentHTML("beforeend", `<th>${dayName}</th>`);
        });
    
        // Create table body rows
        roomNames.forEach(roomName => {
            let row = `<tr><td>${roomName}</td>`;
            days.forEach(day => {
                const dayString = day.toISOString().split('T')[0];
    
                // Check for events
                let cell = `<td onclick="openPopup('${roomName}', '', '${dayString}')"></td>`;
                events.forEach(event => {
                    if (event && event.room === roomName && event.booking_date === dayString) {
                        cell = `<td class="event ${event.cssClass || ''}" onclick="showEventDetails(${parseInt(event.id)}, '${event.name}', '${event.role}')">${event.title || ''}</td>`;
                    }
                });
    
                row += cell;
            });
    
            row += "</tr>";
            tableBody.insertAdjacentHTML("beforeend", row);
        });
    }else if (selectedView === "month") {
        // Generate all days for the current month
        const days = [];
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        const totalDays = new Date(year, month + 1, 0).getDate();
        for (let i = 1; i <= totalDays; i++) {
            days.push(new Date(year, month, i));
        }
    
        // Create table headers
        headerRow.insertAdjacentHTML("beforeend", "<th>Room</th>");
        days.forEach(day => {
            // Extract only the date (e.g., "01", "02")
            const dayNumber = day.getDate().toString().padStart(2, '0'); // Add leading zero for single-digit days
            headerRow.insertAdjacentHTML("beforeend", `<th>${dayNumber}</th>`);
        });
    
        // Create table body rows
        roomNames.forEach(roomName => {
            let row = `<tr><td>${roomName}</td>`;
            days.forEach(day => {
                const dayString = day.toISOString().split('T')[0];
    
                // Check for events
                let cell = `<td onclick="openPopup('${roomName}', '', '${dayString}')"></td>`;
                events.forEach(event => {
                    if (event && event.room === roomName && event.booking_date === dayString) {
                        cell = `<td class="event ${event.cssClass || ''}" onclick="showEventDetails(${parseInt(event.id)}, '${event.name}', '${event.role}')">${event.title || ''}</td>`;
                    }
                });
    
                row += cell;
            });
    
            row += "</tr>";
            tableBody.insertAdjacentHTML("beforeend", row);
        });
    }
}


function showEventDetails(eventId, name, role) {
    if (!eventId) {
        console.error("Event ID not found.");
        return;
    }

    const event = eventsData.find(e => e.id === eventId);

    if (event) {
        document.getElementById('modalEventTitle').textContent = event.title || 'Event Details';
        document.getElementById('modalEventRoom').textContent = event.room || 'N/A';
        document.getElementById('modalEventName').textContent = name || 'N/A';
        document.getElementById('modalEventRole').textContent = role || 'N/A';
        document.getElementById('modalEventDate').textContent = event.booking_date || 'N/A';
        document.getElementById('modalEventStartTime').textContent = event.start || 'N/A';
        document.getElementById('modalEventEndTime').textContent = event.end || 'N/A';
        document.getElementById('modalEventDescription').textContent = event.description || 'N/A';
        document.getElementById('modalEventStatus').textContent = event.status || 'N/A';

        document.getElementById('eventModal').style.display = 'block';
    } else {
        console.error("Event not found for ID:", eventId);
    }
}

function closeModal() {
    document.getElementById('eventModal').style.display = 'none';
}

window.addEventListener('click', function (event) {
    const modal = document.getElementById('eventModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});

function openPopup(room, time, currentDate) {
    const popup = document.getElementById('popup');
    const roomInput = document.getElementById('ruangan');
    const timeInput = document.getElementById('starttime');
    const dateInput = document.getElementById('bookingdate');

    // Use the passed currentDate without any fallback or overwrite
    console.log("Room:", room);
    console.log("Time:", time);
    console.log("Current Date (passed):", currentDate);

    // Set the input values
    if (roomInput) roomInput.value = room;
    if (timeInput) timeInput.value = time;
    if (dateInput) dateInput.value = currentDate; // Directly use currentDate

    // Show the popup
    popup.classList.remove('hidden');
}