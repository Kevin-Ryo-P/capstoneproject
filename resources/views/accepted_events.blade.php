<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accepted Upcoming Events</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/accepted_events.css') }}">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <div class="nav">
        @include('partials.dashboardnavbar')
    </div>
    <div class="dash-main">
        <h2 class="title">Accepted Upcoming Events</h2>
        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Event Name</th>
                        <th>Room</th>
                        <th>Booking Date</th>
                        <th>Start - End Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($acceptedBookings as $booking)
                        <tr>
                            <td>{{ $booking->name }}</td>
                            <td>{{ $booking->role }}</td>
                            <td>{{ $booking->event_name }}</td>
                            <td>{{ $booking->room }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td>
                            <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                            <td>
                                <form action="{{ route('update.status', $booking->id) }}" method="POST" class="action-form">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="canceled">
                                    <!-- Use SweetAlert2 for confirmation -->
                                    <button type="button" class="action-button cancel" onclick="confirmWithSweetAlert(this)">Cancel</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="no-events">No accepted bookings for today.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Function to show SweetAlert2 confirmation
        function confirmWithSweetAlert(button) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you really want to cancel this event?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form if confirmed
                    button.closest('form').submit();
                }
            });
        }
    </script>
</body>
</html>
