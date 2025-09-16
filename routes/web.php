<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EventBookingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\Room;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AdminAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them
| will be assigned to the "web" middleware group.
|
*/


Route::get('/', function () {
    return redirect()->route('login');
});

// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('home', [
            "title" => "Home"
        ]);
    });

    Route::get('/peminjaman-ruangan', function () {
        return view('peminjaman', [
            "title" => "Peminjaman Ruangan"
        ]);
    });
});

// Registration routes
// Student registration routes
Route::get('/register/student', [RegisterController::class, 'showStudentRegisterForm'])->name('register.student');
Route::post('/register/student', [RegisterController::class, 'registerStudent'])->name('register.student.post');

// Professor registration routes
Route::get('/register/professor', [RegisterController::class, 'showProfessorRegisterForm'])->name('register.professor');
Route::post('/register/professor', [RegisterController::class, 'registerProfessor'])->name('register.professor.post');

// Dashboard and other existing routes...
Route::get('/dashboard', function () {
    return view('dashboardhome', [
        "title" => "Dashboard"
    ]);
});

Route::get('/booking-history', [EventBookingController::class, 'showBookingHistory'])->name('booking.history');

Route::get('/konfirmasi', function () {
    return view('dashboardkonfirmasi', [
        "title" => "Konfirmasi"
    ]);
});

Route::get('/status-ruangan', function () {
    return view('statusruang', [
        "title" => "Status Ruangan"
    ]);
});

Route::get('/users', function () {
    return view('users', [
        "title" => "Users"
    ]);
});

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

Route::get('/dashboard-register', function () {
    return view('dashboardregister', [
        "title" => "Register Admin"
    ]);
});

Route::get('/events/{id}', [EventController::class, 'show']);
Route::post('/event-booking', [EventBookingController::class, 'store']);
Route::get('/api/events', [EventBookingController::class, 'getEvents']);
Route::get('/konfirmasi', [EventBookingController::class, 'showDashboard'])->name('dashboard.konfirmasi');
Route::get('/users', [UserController::class, 'showUsers'])->name('users.index');
Route::post('/events/bulk-update', [EventBookingController::class, 'bulkUpdate'])->name('events.bulkUpdate');

Route::get('/api/rooms', function () {
    return Room::all();
});


Route::resource('rooms', RoomController::class)->middleware('auth');
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/ruangan', [RoomController::class, 'listRooms'])->name('rooms.list');
Route::get('/ruangan/{room}', [RoomController::class, 'show'])->name('rooms.show');
Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');
Route::get('/search-rooms', [RoomController::class, 'search'])->name('rooms.search');
Route::get('/room-details/{name}', [RoomController::class, 'getRoomDetails']);
Route::get('/rooms/details/{name}', [RoomController::class, 'getRoomDetails'])->name('rooms.details');



Route::get('/accepted-events', [EventBookingController::class, 'showAcceptedEventsForToday'])->name('accepted.events');
Route::delete('/delete-booking/{id}', [EventBookingController::class, 'deleteBooking'])->name('delete.booking');
Route::patch('/update-status/{id}', [EventBookingController::class, 'updateStatus'])->name('update.status');


Route::middleware(['auth'])->group(function () {
    Route::get('/my-bookings', [EventBookingController::class, 'showUserBookings'])->name('user.bookings');
});

Route::get('/edit-profile', [UserController::class, 'editProfile'])->name('edit-profile');
Route::post('/update-profile', [UserController::class, 'updateProfile'])->name('update-profile');

Route::get('/api/rooms', function () {
    return \App\Models\Room::all(['name', 'location', 'capacity']);
});

Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::get('/admin/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard')->middleware('admin');
Route::post('/adminlogout', function () {Auth::logout();return redirect('/admin/login');})->name('admin.logout');

// Menampilkan form registrasi
Route::get('/register', [RegisterController::class, 'showStudentRegisterForm'])->name('register.student');
Route::get('/dashboardregister', [RegisterController::class, 'showProfessorRegisterForm'])->name('register.professor');

// Proses registrasi
Route::post('/register', [RegisterController::class, 'registerStudent'])->name('register.student.submit');
Route::post('/dashboardregister', [RegisterController::class, 'registerProfessor'])->name('register.professor.submit');

// Proses cancel by user
Route::patch('/user/cancel-booking/{id}', [EventBookingController::class, 'cancelByUser'])->name('user.cancel.booking');

Route::get('/cancelled-bookings', [EventBookingController::class, 'showCancelledBookings'])->name('cancelled.bookings');

Route::middleware(['auth'])->group(function () {
    Route::get('/cancelled-bookings', [EventBookingController::class, 'showCancelledBookings'])->name('cancelled.bookings');
});
