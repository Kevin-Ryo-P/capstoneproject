<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/room_details.css') }}">
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
                <h2 class="form-title">{{ $room->name }}</h2>
                <div class="room-content">
                    @if ($room->image)
                        <div class="room-image">
                            <img src="{{ asset('storage/' . $room->image) }}" alt="Room Image">
                        </div>
                    @endif
                    <div class="room-info">
                        <p><strong>Location:</strong> {{ $room->location }}</p>
                        <p><strong>Capacity:</strong> {{ $room->capacity }}</p>
                        <p><strong>Description:</strong> {{ $room->description ?? 'No description available' }}</p>
                    </div>
                </div>

                @if ($room->blueprint)
                    <div class="room-blueprint">
                        <h3>Denah Ruangan</h3>
                        @if (pathinfo($room->blueprint, PATHINFO_EXTENSION) === 'pdf')
                            <a href="{{ asset('storage/' . $room->blueprint) }}" target="_blank" class="blueprint-link">View Blueprint (PDF)</a>
                        @else
                            <img src="{{ asset('storage/' . $room->blueprint) }}" alt="Room Blueprint">
                        @endif
                    </div>
                @endif

                <a href="{{ route('rooms.list') }}" class="back-link">Back to Room List</a>
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
