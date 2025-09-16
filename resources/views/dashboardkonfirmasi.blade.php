<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/dashboardkonfirmasi.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
    <title>{{ $title }}</title>
</head>
<body>
    <div class="nav">
        @include('partials.dashboardnavbar')
    </div>
    <form id="eventForm" action="{{ route('events.bulkUpdate') }}" method="POST">
        @csrf
        <div class="dash-main">
            <div class="main">
                <div class="option-holder">
                    <h1 class="page-title">Konfirmasi Request</h1>
                    <div class="sort-container">
                        <label for="sort" class="form-label">Sort By:</label>
                        <select name="sort" id="sort" class="form-select">
                            <option value="default">Default</option>
                            <option value="date">Date</option>
                            <option value="role">Role</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn primary-btn">Bulk Submit</button>
                    </div>
                </div>


                <div id="events-container">
                    @foreach ($events as $event)
                        <div class="event-card {{ $event->is_conflict ? 'conflict' : '' }}">
                            <div class="event-info">
                                <p class="room">Room: {{ $event->room }}</p>
                                <p class="event-name">{{ $event->event_name }}</p>
                                <p class="event-desc">{{ $event->description }}</p>
                                <p class="user-info">{{ $event->name }} ({{ $event->role }})</p>
                                <p class="date-time">{{ \Carbon\Carbon::parse($event->booking_date)->format('m/d/Y') }} | {{ $event->start_time }} - {{ $event->end_time }}</p>
                            </div>
                            <div class="actions">
                                <div class="checkbox-group">
                                    <label class="checkbox-container">
                                        <input type="checkbox" name="statuses[{{ $event->id }}]" value="accepted" class="confirm-box" onclick="toggleCheckbox(this)" {{ $event->is_conflict ? 'disabled' : '' }}>
                                        <span class="checkbox-container"></span>
                                        Accept
                                    </label>
                                    <label class="checkbox-container">
                                        <input type="checkbox" name="statuses[{{ $event->id }}]" value="denied" class="deny-box" onclick="toggleCheckbox(this)">
                                        <span class="checkbox-container"></span>
                                        Deny
                                    </label>
                                </div>
                                @if ($event->permit_picture)
                                    <button type="button" class="view-permit-btn" onclick="viewPermit('{{ asset('storage/' . $event->permit_picture) }}')">View Permit</button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </form>

<script>
    // Sorting Logic
    const events = @json($events);

    document.getElementById('sort').addEventListener('change', function () {
        const sortValue = this.value;
        let sortedEvents = [...events];

        if (sortValue === 'date') {
            sortedEvents.sort((a, b) => new Date(a.booking_date) - new Date(b.booking_date));
        } else if (sortValue === 'role') {
            sortedEvents.sort((a, b) => a.role.localeCompare(b.role));
        }

        renderEvents(sortedEvents);
    });

    // Ensure only one checkbox per event can be selected
    function toggleCheckbox(selectedCheckbox) {
        const checkboxes = selectedCheckbox.closest('.event-card').querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            if (checkbox !== selectedCheckbox) checkbox.checked = false;
        });
    }

    // Re-render events (used for sorting)
    function renderEvents(sortedEvents) {
        const container = document.getElementById('events-container');
        container.innerHTML = '';

        sortedEvents.forEach(event => {
            const card = `
                <div class="event-card ${event.is_conflict ? 'conflict' : ''}">
                    <div class="event-info">
                        <p class="room">Room: ${event.room}</p>
                        <p class="event-name">${event.event_name}</p>
                        <p class="event-desc">${event.description}</p>
                        <p class="user-info">${event.name} (${event.role})</p>
                        <p class="date-time">${new Date(event.booking_date).toLocaleDateString()} | ${event.start_time} - ${event.end_time}</p>
                    </div>
                    <div class="checkbox-group">
                        <label class="checkbox-container">
                            <input type="checkbox" name="statuses[${event.id}]" value="accepted" class="confirm-box" ${event.is_conflict ? 'disabled' : ''}>
                            <span class="checkbox-container"></span> Accept
                        </label>
                        <label class="checkbox-container">
                            <input type="checkbox" name="statuses[${event.id}]" value="denied" class="deny-box">
                            <span class="checkbox-container"></span> Deny
                        </label>
                    </div>
                </div>
            `;
            container.innerHTML += card;
        });
    }

    function viewPermit(permitUrl) {
        event.preventDefault(); // Prevent the default button behavior
        const modalHtml = `
            <div id="permit-modal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeModal()">&times;</span>
                    <img src="${permitUrl}" alt="Permit Image" class="permit-image">
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        document.getElementById('permit-modal').style.display = 'block';
    }

    function closeModal() {
        const modal = document.getElementById('permit-modal');
        if (modal) modal.remove();
    }

</script>
</body>
</html>
