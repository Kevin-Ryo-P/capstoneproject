<!DOCTYPE html>
<html lang="en">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
    <title>Register</title>
</head>
<body>
<div class="elements">
    <div class="body-replace">
        <div class="register-container">
            <form action="{{ route('register.professor') }}" method="POST">
                @csrf
                <h1>Register</h1>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="form-row">
                    <input type="text" name="name" id="nameInput" class="form-input" placeholder="Your Name">
                    <label for="nameInput" class="form-label">Name</label>
                </div>
                <div class="form-row">
                    <input type="email" name="email" id="emailInput" class="form-input" placeholder="example@email.com">
                    <label for="emailInput" class="form-label">Email</label>
                </div>
                <div class="form-row">
                    <input type="password" name="password" id="passwordInput" class="form-input" placeholder="1234password">
                    <label for="passwordInput" class="form-label">Password</label>
                </div>
                <div class="form-row">
                    <input type="password" name="password_confirmation" id="passwordConfirmationInput" class="form-input" placeholder="Confirm Password">
                    <label for="passwordConfirmationInput" class="form-label">Confirm Password</label>
                </div>
                <div class="form-row">
                    <select name="role" class="form-input">
                        <option value="professor">Professor</option>
                        <option value="admin">Admin</option>
                    </select>
                    <label for="role" class="form-label">Role</label>
                </div>

                <button type="submit" class="submitBtn">Register</button>
            </form>
            <p class="loginText">Already have an account? <a href="{{ route('login') }}">Login</a></p>
        </div>
    </div>
</div>
</body>
</html>