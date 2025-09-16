<!DOCTYPE html>
<html lang="en">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
    <title>{{ $title }}</title>
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="elements">
    <div class="body-replace">
        <div class="login-container">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <h1>Login</h1>
                <div class="form-row">
                    <input type="email" name="email" id="emailInput" class="form-input" placeholder="example@email.com" value="{{ old('email') }}">
                    <label for="emailInput" class="form-label">Email</label>
                </div>
                <div class="form-row">
                    <input type="password" name="password" id="passwordInput" class="form-input" placeholder="1234password">
                    <label for="passwordInput" class="form-label">Password</label>
                </div>
                <a href="#" class="forgotPwd">Forgot password?</a>

                <button type="submit" class="submitBtn">Login</button>
            </form>
            <p class="signUpText">Don't have an account? <a href="{{ route('register.student') }}">Sign Up</a></p>
        </div>
    </div>
</div>


@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif

</body>
</html>
