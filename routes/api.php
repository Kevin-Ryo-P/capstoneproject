<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventBookingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('event-booking', [EventBookingController::class, 'store'])->name('event-booking.store');
Route::get('event-bookings', [EventBookingController::class, 'index'])->name('event-booking.getEvents');
Route::post('event-booking/{id}/update-status', [EventBookingController::class, 'updateStatus'])->name('event-booking.updateStatus');
Route::get('dashboard/events', [EventBookingController::class, 'showDashboard'])->name('dashboard.konfirmasi');
Route::post('event-booking/bulk-update', [EventBookingController::class, 'bulkUpdate'])->name('event-booking.bulkUpdate');
Route::get('event-booking/history', [EventBookingController::class, 'showBookingHistory'])->name('event-booking.history');
Route::get('event-booking/accepted-today', [EventBookingController::class, 'showAcceptedEventsForToday'])->name('accepted.events');
Route::delete('event-booking/{id}', [EventBookingController::class, 'deleteBooking'])->name('event-booking.delete');
Route::get('event-booking/user', [EventBookingController::class, 'showUserBookings'])->name('user.bookings');
Route::delete('/booking/{id}', [EventBookingController::class, 'destroy'])->middleware('admin');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

Route::get('/event-bookings', [EventBookingController::class, 'index'])->name('eventBookings.index');
Route::get('/users-json', function () {return response()->json(\App\Models\User::all()); });
