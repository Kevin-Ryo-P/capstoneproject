<link rel="stylesheet" href="{{ asset('css/dashboardnavbar.css') }}">
<div class="dash-container">
    <div class="dash-nav">
        <div class="dashboard-option">
            <img class="dashNavicons" src="{{ asset('img/dashboard.svg') }}" alt="">
            <a href="/dashboard"><p class="navText">Dashboard</p></a>
        </div>

        <div class="dashboard-option">
            <img class="dashNavicons" src="{{ asset('img/home.svg') }}" alt="">
            <a href="/"><p class="navText">Home</p></a>
        </div>
        <div class="dashboard-option">
            <img class="dashNavicons" src="{{ asset('img/calendar.svg') }}" alt="">
            <a href="/konfirmasi"><p class="navText">Konfirmasi Request</p></a>
        </div>
        <div class="dashboard-option">
            <img class="dashNavicons" src="{{ asset('img/accepted.svg') }}" alt="">
            <a href="/accepted-events"><p class="navText">accepted</p></a>
        </div>
        <div class="dashboard-option">
            <img class="dashNavicons" src="{{ asset('img/history.svg') }}" alt="">
            <a href="/booking-history"><p class="navText">History</p></a> 
        </div>
        <div class="dashboard-option">
            <img class="dashNavicons" src="{{ asset('img/user.svg') }}" alt="">
            <a href="/users"><p class="navText">Users</p></a>
        </div>
        <div class="dashboard-option">
            <img class="dashNavicons" src="{{ asset('img/rooms.svg') }}" alt="">
            <a href="/rooms"><p class="navText">Rooms</p></a> 
        </div>
        <div class="dashboard-option">
            <img class="dashNavicons" src="{{ asset('img/logout.svg') }}" alt="">
            <a href="#" class="logout-link" style="text-decoration: none;">
                <p class="navText">Logout</p>
            </a>
        </div>

        <script>
            document.querySelector('.logout-link').addEventListener('click', function (e) {
                e.preventDefault();

                // Create a hidden form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('admin.logout') }}';

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                form.appendChild(csrfToken);
                document.body.appendChild(form);

                form.submit();
            });
        </script>


    </div>
</div>
