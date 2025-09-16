<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
    <title>{{ $title }}</title>
</head>
<body>
    {{-- pembagian kelas grid--}}
    <div class="website-body">
        {{-- bagian header --}}
        <div class="header">
            @include('partials.navbar')
        </div>
        {{-- grid tabel --}}
        <div class="main">
            {{-- manggil table --}}
            @include('partials.tables')
        </div>
        {{-- grid search bar & calendar --}}
        <div class="secondary">
            <div class="search-bar-holder">
                <input class="search-bar" type="text">
            </div>
            {{-- manggil calendar --}}
            @include('partials.calendar')
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
                    <p class="cat-text">Maintanance</p>
                    <div class="cat-maint"></div>
                </div>
                <div class="cat-holder">
                    <p class="cat-text">Zoom</p>
                    <div class="cat-zoom"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Popup -->
    <div id="popup" class="popup hidden">
        <div class="popup-content">
            <span id="close-popup" class="close-popup">&times;</span>
            <div class="form-container">
                <div class="holder">
                    <div class="data-input">
                    <form id="event-form" method="POST" enctype="multipart/form-data">
                            <!-- Existing form structure -->
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
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    const popup = document.getElementById('popup');
    const openPopupLink = document.querySelector('.calendarOpt a');
    const closePopup = document.getElementById('close-popup');

    openPopupLink.addEventListener('click', function (e) {
        e.preventDefault();
        popup.classList.remove('hidden');
    });

    closePopup.addEventListener('click', function () {
        popup.classList.add('hidden');
    });

    window.addEventListener('click', function (event) {
        if (event.target === popup) {
            popup.classList.add('hidden');
        }
    });
});

$(document).ready(function () {
    $('#submit-btn').click(function (e) {
        e.preventDefault();

        // Initialize FormData object
        const formData = new FormData();
        formData.append('room', $('#ruangan').val());
        formData.append('booking_date', $('#bookingdate').val());
        formData.append('start_time', $('#starttime').val());
        formData.append('end_time', $('#endtime').val());
        formData.append('event_type', $('#event-type').val());
        formData.append('event_name', $('.nama-acara').val());
        formData.append('description', $('.deskripsi-acara').val());
        formData.append('status', 'pending');
        formData.append('_token', '{{ csrf_token() }}');

        // Add the permit picture file if present
        const permitPicture = $('#permit_picture')[0].files[0];
        if (permitPicture) {
            formData.append('permit_picture', permitPicture); // Add the file to the formData
        }

        $.ajax({
            url: '/event-booking',
            type: 'POST',
            data: formData,
            processData: false, // Prevent jQuery from processing the data
            contentType: false, // Prevent jQuery from setting Content-Type
            success: function (response) {
                Swal.fire({
                    title: 'Success!',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    $('#popup').addClass('hidden');
                    $('#event-form')[0].reset();
                });
            },
            error: function (xhr) {
            if (xhr.status === 422) {
                let response = xhr.responseJSON;
                let errorMessage = response.message || 'Validation error occurred';
                Swal.fire({
                    title: 'Booking Conflict',
                    text: errorMessage,
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred: ' + xhr.responseText,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        }

        });
    });
});

document.getElementById('ruangan').addEventListener('change', function () {
    const roomName = this.value;
    fetch(`/room-details/${roomName}`)
        .then(response => response.json())
        .then(data => {
            if (data.location) {
                Swal.fire({
                    title: 'Room Details',
                    text: 'Room location: ' + data.location,
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            } else {
                console.error('Room not found');
            }
        })
        .catch(error => {
            Swal.fire({
                title: 'Error',
                text: 'Error fetching room details: ' + error,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
});
</script>


</body>
</html>