<link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Roboto:wght@400;700&display=swap" rel="stylesheet">

<div class="navbar">
    <div class="logo">
        <img src="{{ asset('img/logoUndip.png') }}" class="logoUndip" alt="">
        
        <div class="logoTextContainer">
            <p class="logoText-fakultas">FAKULTAS TEKNIK</p>
            <p class="logoText-dipo">UNIVERSITAS DIPONEGORO</p>
        </div>
    </div>
    <div class="navbarMenu">
        <div class="navbarMenuOpt">
            <img class="navbar-icon-image" src="{{ asset('img/iconsRoomInformation.svg') }}" alt="">
            <a class="navbar-icon" href="/home"><p>Event Information</p></a>
        </div>

        <span class="fa-stack">
            <i class="far fa-calendar fa-stack-2x"></i>
            <strong class="fa-stack-1x calendar-text" id="date"></strong>
        </span>
        
        <div class="calendarOpt">
            <a href="javascript:void(0);" id="open-popup-link"><p>Peminjaman Ruangan</p></a>
        </div>

        <div class="navbarMenuOpt">
            <img class="navbar-icon-image" src="{{ asset('img/status.svg') }}" alt="">
            <a class="navbar-icon" href="/my-bookings"><p>Bookings Saya</p></a>
        </div>

        <!-- Tambahan Cancelled Bookings -->
        <div class="navbarMenuOpt">
            <img class="navbar-icon-image" src="{{ asset('img/status.svg') }}" alt="">
            <a class="navbar-icon" href="{{ route('cancelled.bookings') }}"><p>Cancelled Bookings</p></a>
        </div>

        <div class="navbarMenuOpt">
            <img class="navbar-icon-image" src="{{ asset('img/classroom2.svg') }}" alt="">
            <a class="navbar-icon" href="/ruangan">View All Rooms</a>
        </div>

    </div>
    <div class="profile-dropdown">
        <img class="profile-img" 
            src="{{ auth()->user() && auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('img/blank-profile.jpg') }}" 
            alt="Profile Image">
        <div class="profile-dropdown-content">
            <a href="/edit-profile">Edit Profile</a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>


</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{ asset('js/navbar.js') }}">
</script>
