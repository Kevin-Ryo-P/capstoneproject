<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room List</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/room_list.css') }}">
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
                    <h2 class="form-title">Room List</h2>
                    <div class="room-container">
                        @forelse ($rooms as $room)
                            <div class="room-card">
                                <h3 class="room-name">{{ $room->name }}</h3>
                                <p><strong>Lantai:</strong> {{ $room->location }}</p>
                                <p><strong>Capacity:</strong> {{ $room->capacity }}</p>
                                @if ($room->image)
                                    <div class="room-image">
                                        <img src="{{ asset('storage/' . $room->image) }}" alt="Room Image">
                                    </div>
                                @endif
                                <a href="{{ route('rooms.show', $room->id) }}" class="details-link">View Details</a>
                            </div>
                        @empty
                            <p class="text-center">No rooms available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="secondary">
            <div class="search-bar-holder">
                <form action="{{ route('rooms.search') }}" method="GET" id="search-form">
                    <input
                        class="search-bar"
                        type="text"
                        name="query"
                        id="search-bar"
                        placeholder="Search for a room..."
                        required>
                </form>
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
</body>
</html>
