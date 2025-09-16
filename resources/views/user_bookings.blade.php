<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link rel="stylesheet" href="{{ asset('css/user_booking.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
</head>
<body>
    <div class="website-body">
        <!-- Header -->
        <div class="header">
            @include('partials.navbar')
        </div>

        <!-- Main Content -->
        <div class="main">
            <div class="form-container">
                <div class="holder">
                    <h2 class="form-title">My Bookings</h2>
                    <div class="data-input">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Event Name</th>
                                    <th>Room</th>
                                    <th>Booking Date</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bookings as $booking)
                                    <tr>
                                        <td>{{ $booking->event_name }}</td>
                                        <td>{{ $booking->room }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td>
                                        <td>{{ $booking->start_time }}</td>
                                        <td>{{ $booking->end_time }}</td>
                                        <td>{{ ucfirst($booking->status) }}</td>
                                        <td>
                                            @if ($booking->status !== 'cancelled')
                                                <form action="{{ route('user.cancel.booking', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" style="background-color: red; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Cancel</button>
                                                </form>
                                            @else
                                                —
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">You have no bookings.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="secondary">
            <div class="search-bar-holder">
                <form action="{{ route('rooms.search') }}" method="GET" id="search-form">
                    <input class="search-bar" type="text" name="query" id="search-bar" placeholder="Search for a room..." required>
                </form>
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            <!-- Calendar -->
            @include('partials.calendar')

            <!-- Categories -->
            <div class="categories">
                <p class="cat-title">Categories</p>
                <div class="cat-holder">
                    <p class="cat-text">External</p>
                    <div class="cat-exter"></div>
                </div>
                <div class="cat-holder">
                    <p class="cat-text">Internal</p>
                    <div class="cat-inter"></div>
                </div>
                <div class="cat-holder">
                    <p class="cat-text">Maintenance</p>
                    <div class="cat-maint"></div>
                </div>
                <div class="cat-holder">
                    <p class="cat-text">Zoom</p>
                    <div class="cat-zoom"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const table = document.querySelector('.table');
            const headers = table.querySelectorAll('th');
            const rows = Array.from(table.querySelector('tbody').rows);

            headers.forEach((header, index) => {
                header.addEventListener('click', () => {
                    const sortedRows = rows.sort((a, b) => {
                        const aData = a.cells[index].innerText;
                        const bData = b.cells[index].innerText;

                        if (index === 2) {
                            return new Date(aData) - new Date(bData);
                        }
                        return aData.localeCompare(bData);
                    });

                    const tbody = table.querySelector('tbody');
                    tbody.innerHTML = '';
                    sortedRows.forEach(row => tbody.appendChild(row));
                });
            });
        });
    </script>
</body>
</html>
