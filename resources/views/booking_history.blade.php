<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/booking_history.css') }}">
</head>
<body>
    <div class="nav">
        @include('partials.dashboardnavbar')
    </div>
    <div class="dash-main">
        <h2 class="title">Booking History</h2>
            <div class="action-container">
                <button id="open-form-btn" class="btn primary-btn">Add New Booking</button>
            </div>
        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Event Name</th>
                        <th>Room</th>
                        <th>Booking Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        <tr>
                            <td>{{ $booking->name }}</td>
                            <td>{{ $booking->role }}</td>
                            <td>{{ $booking->event_name }}</td>
                            <td>{{ $booking->room }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td>
                            <td>
                                <span class="status-badge {{ $booking->status }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="no-bookings">No bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for the form -->
    <div id="form-modal" class="modal hidden">
        <div class="modal-content">
            <span id="close-modal" class="close-modal">&times;</span>
            <form id="event-form" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="ruangan" class="form-label">Pilih ruangan:</label>
                    <select name="ruangan" id="ruangan" class="form-select">
                        <option value="A201">A201</option>
                        <option value="A202">A202</option>
                        <option value="A102">A102</option>
                        <option value="A101">A101</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="bookingdate" class="form-label">Pilih tanggal:</label>
                    <input type="date" id="bookingdate" name="bookingdate" class="form-input">
                </div>
                <div class="form-group">
                    <label for="starttime" class="form-label">Start time:</label>
                    <input type="time" id="starttime" name="starttime" class="form-input time-input">
                </div>
                <div class="form-group">
                    <label for="endtime" class="form-label">End time:</label>
                    <input type="time" id="endtime" name="endtime" class="form-input time-input">
                </div>
                <div class="form-group">
                    <label for="event-type" class="form-label">Pilih jenis acara:</label>
                    <select name="event-type" id="event-type" class="form-select">
                        <option value="External">External</option>
                        <option value="Internal">Internal</option>
                        <option value="Maintenance">Maintenance</option>
                        <option value="Zoom">Zoom</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="event_name" class="form-label">Acara:</label>
                    <input type="text" id="event_name" name="event_name" class="form-input nama-acara">
                </div>
                <div class="form-group">
                    <label for="description" class="form-label">Deskripsi:</label>
                    <textarea id="description" name="description" class="form-input deskripsi-acara" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label for="permit_picture" class="form-label">Upload Surat: *optional</label>
                    <input type="file" id="permit_picture" name="permit_picture" class="form-input" accept="image/*">
                </div>
                <div class="button-container">
                    <input type="submit" id="submit-btn" value="Submit">
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('form-modal');
            const openBtn = document.getElementById('open-form-btn');
            const closeBtn = document.getElementById('close-modal');

            openBtn.addEventListener('click', function () {
                modal.classList.remove('hidden');
            });

            closeBtn.addEventListener('click', function () {
                modal.classList.add('hidden');
            });

            window.addEventListener('click', function (event) {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
        document.getElementById('open-form-btn').addEventListener('click', function () {
            document.getElementById('form-modal').style.display = 'block';
        });

        document.getElementById('close-modal').addEventListener('click', function () {
            document.getElementById('form-modal').style.display = 'none';
        });

        window.addEventListener('click', function (event) {
            const modal = document.getElementById('form-modal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    </script>
</body>
</html>