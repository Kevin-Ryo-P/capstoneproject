<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
    <title>dashboard</title>
</head>
<body>
    <div class="nav">
    @include('partials.dashboardnavbar')
    </div>
        <div class="dash-main">
            <p>Welcome, Admin</p>
        </div>
</body>
</html>
