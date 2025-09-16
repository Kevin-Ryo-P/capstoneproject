<!DOCTYPE html>
<html lang="en">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Admin Login</title>
</head>

<body>
<div class="elements">
    <div class="body-replace">
        <div class="login-container">
            <form action="{{ route('admin.login.post') }}" method="POST">
                @csrf
                <h1>Admin Login</h1>
                <div class="form-row">
                    <input type="email" name="email" id="emailInput" class="form-input" placeholder="example@email.com" value="{{ old('email') }}">
                    <label for="emailInput" class="form-label">Email</label>
                </div>
                <div class="form-row">
                    <input type="password" name="password" id="passwordInput" class="form-input" placeholder="1234password">
                    <label for="passwordInput" class="form-label">Password</label>
                </div>
                <button type="submit" class="submitBtn">Login</button>
            </form>
        </div>
    </div>
</div>

<!-- SweetAlert Error Handling -->
@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Login Failed',
            html: `
                <ul style="text-align: left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
        });
    </script>
@endif

</body>

</html>
