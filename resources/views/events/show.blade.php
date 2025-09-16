<!-- resources/views/events/show.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
</head>
<body>
    <div id="eventModal" class="modal">
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
</body>
</html>
<script src="{{ asset('js/tables.js') }}"></script>