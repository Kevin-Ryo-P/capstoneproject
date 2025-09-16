<?php

    namespace App\Http\Controllers;

    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Storage;


    class UserController extends Controller
    {
        public function index()
        {
            $users = User::all(); // Fetch all users
            return view('users', [
                'title' => 'User Information',
                'users' => $users
            ]);
        }
        public function showUsers()
        {
            $users = User::all();
            return view('users', [
                'title' => 'Users',
                'users' => $users,
            ]);
        }

        public function editProfile()
        {
            $user = auth()->user(); // Get the authenticated user
            return view('edit_profile', [
                'title' => 'Edit Profile',
                'user' => $user
            ]);
        }
        
        public function updateProfile(Request $request)
        {
            $user = auth()->user();

            if (!$user) {
                return back()->with('error', 'User not found.');
            }

            // Pastikan $user adalah instance dari User
            if (!$user instanceof User) {
                return back()->with('error', 'Invalid user instance.');
            }
            
            // Validate input data
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                // Delete old profile image if it exists
                if ($user->profile_image && Storage::exists('public/' . $user->profile_image)) {
                    Storage::delete('public/' . $user->profile_image);
                }

                // Store the new profile image
                $path = $request->file('profile_image')->store('profile_images', 'public');

                // Update profile image path
                $user->profile_image = $path;
            }

            // Update user details
            $user->name = $request->name;
            $user->email = $request->email;

            // Saving changes to Database
            $user->save();

            return redirect()->route('edit-profile')->with('success', 'Profile updated successfully!');
        }

        public function store(Request $request)
        {
            // Validate the input data
            $request->validate([
                'role' => 'required|in:Admin,Professor,Student',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6', // Add a default password
            ]);

            // Create a new user
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password), // Hash the password
                'role' => $request->role,
            ]);

            // Redirect back with success message
            return redirect()->route('users.index')->with('success', 'User added successfully!');
        }

        public function destroy($id)
        {
            $user = User::find($id);

            if ($user) {
                $user->delete();

                return response()->json(['success' => true, 'message' => 'User deleted successfully!']);
            }

            return response()->json(['success' => false, 'message' => 'User not found!'], 404);
        }


    }
