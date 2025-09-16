<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Management</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/room_management.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="nav">
        @include('partials.dashboardnavbar')
    </div>
    <div class="dash-main">
        <h1 class="title">Room Management</h1>

        @if(session('success'))
            <div class="alert success-alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Add Room Form -->
        <form action="{{ route('rooms.store') }}" method="POST" class="add-room-form">
            @csrf
            <input type="text" name="name" placeholder="Room Name" required>
            <input type="text" name="location" placeholder="Location (Optional)">
            <input type="number" name="capacity" placeholder="Capacity (Optional)">
            <button type="submit" class="btn primary-btn">Add Room</button>
        </form>

        <!-- Room List -->
        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Capacity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                        <tr>
                            <td>{{ $room->id }}</td>
                            <td>{{ $room->name }}</td>
                            <td>{{ $room->location ?? 'N/A' }}</td>
                            <td>{{ $room->capacity ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('rooms.edit', $room) }}" class="btn edit-btn">Edit</a>
                                <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="inline-form delete-form" data-id="{{ $room->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn delete-btn" onclick="confirmDelete({{ $room->id }})">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-row">No rooms found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <script>
    function confirmDelete(roomId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the delete form
                document.querySelector(`form[data-id='${roomId}']`).submit();
            }
        });
    }
</script>

</body>
</html>
