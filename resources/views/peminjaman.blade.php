<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/peminjaman.css') }}">
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
        <div class="form-container">
            <div class="holder">
                <div class="data-input">
                    <form id="event-form">
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

                        <div class="form-group date-time">
                            <div class="time-group">
                                <label for="starttime" class="form-label">Start time:</label>
                                <input type="time" id="starttime" name="starttime" class="form-input time-input">
                            </div>
                            <div class="time-group">
                                <label for="endtime" class="form-label">End time:</label>
                                <input type="time" id="endtime" name="endtime" class="form-input time-input">
                            </div>
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

                        <div class="form-group keterangan">
                            <label for="event_name" class="form-label">Acara:</label>
                            <input type="text" id="event_name" name="event_name" class="form-input nama-acara">
                            
                            <label for="description" class="form-label">Deskripsi:</label>
                            <textarea id="description" name="description" class="form-input deskripsi-acara" rows="5"></textarea>
                        </div>

                        <div class="button-container">
                            <input type="submit" id="submit-btn" value="Submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>



            {{-- manggil table --}}
            <div class="table-holder">
                @include('partials.tables')
            </div>
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
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#submit-btn').click(function(e) {
        e.preventDefault();

        const formData = {
            room: $('#ruangan').val(),
            booking_date: $('#bookingdate').val(),
            start_time: $('#starttime').val(),
            end_time: $('#endtime').val(),
            event_type: $('#event-type').val(),
            event_name: $('.nama-acara').val(),
            description: $('.deskripsi-acara').val(),
            status: 'pending',
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: '/event-booking',
            type: 'POST',
            data: formData,
            success: function(response) {
                alert(response.message);
            },

            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = JSON.parse(xhr.responseText);
                    alert(errors.message); // Show validation error message
                } else {
                    alert('An error occurred: ' + xhr.responseText);
                }
            }

        });
    });
});
</script>
</html>