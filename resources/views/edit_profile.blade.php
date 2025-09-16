<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="{{ asset('css/edit_profile.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
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
            <h2 class="form-title">Edit Profile</h2>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form action="{{ route('update-profile') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                    @error('name') <small class="error">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="profile_image">Profile Image</label>
                    @if ($user->profile_image)
                        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Image" class="profile-image">
                    @endif
                    <input type="file" name="profile_image" id="profile_image">
                    @error('profile_image') <small class="error">{{ $message }}</small> @enderror
                </div>

                <button type="submit">Update Profile</button>
            </form>
        </div>
    </div>
    </div>
</body>
</html>
