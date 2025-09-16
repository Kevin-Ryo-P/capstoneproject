<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventBooking;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\CancelledEventBooking;


class EventBookingController extends Controller
{
    public function index()
    {
        // Ambil semua data event
        $events = EventBooking::all();

        // Kembalikan response JSON dengan data events
        return response()->json($events);
    }

    public function store(Request $request)
    {
        $request->validate([
            'room' => 'required|string|exists:rooms,name',
            'booking_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'event_type' => 'required|string',
            'event_name' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'permit_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Add validation for the picture
        ]);

        // Check for conflicts
        $isConflict = EventBooking::where('room', $request->room)
            ->where('booking_date', $request->booking_date)
            ->where('status', 'accepted') // Ensure only accepted bookings are considered
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->exists();

            if ($isConflict) {
                return response()->json([
                    'message' => 'The room is already booked for the selected date and time.'
                ], 422);
            }

        // Fetch the location of the room
        $room = \App\Models\Room::where('name', $request->room)->first();
        if (!$room) {
            return response()->json(['message' => 'Room not found.'], 404);
        }

        // Handle permit picture upload
        $permitPath = null;
        if ($request->hasFile('permit_picture')) {
            // Jika ada file gambar, simpan gambar dan path-nya
            $permitPath = $request->file('permit_picture')->store('permits', 'public');
        } else {
            // Jika tidak ada file gambar, catat log bahwa tidak ada file yang diupload
            Log::info('No permit file uploaded.');
        }

        // Get the authenticated user
        $user = auth()->user();

        // Create a new event booking
        $eventBooking = EventBooking::create([
            'room' => $request->room,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'event_type' => $request->event_type,
            'event_name' => $request->event_name,
            'description' => $request->description,
            'status' => $request->status,
            'user_id' => $user->id, 
            'name' => $user->name, 
            'role' => $user->role, 
            'location' => $room->location,
            'room_id' => $room->id,
            'permit_picture' => $permitPath, // Save the permit picture path
        ]);

        return response()->json(['message' => 'Event booked successfully!'], 201);
    }


        // Add this method to fetch events
        public function getEvents()
        {
            $events = EventBooking::with('user')->get();

            $formattedEvents = $events->map(function ($event) {

                return [
                    'id' => $event->id,
                    'title' => $event->event_name,
                    'room' => $event->room,
                    'booking_date' => $event->booking_date,
                    'start' => $event->start_time,
                    'end' => $event->end_time,
                    'status' => $event->status,
                    'cssClass' => 'event-' . strtolower(str_replace(' ', '-', $event->event_type)),
                    'name' => $event->name,
                    'role' => $event->role,
                    'user_id' => $event->user_id, 
                    'description' => $event->description,
                    'location' => $event->location,
                    'permit_picture' => $event->permit_picture,
                ];
            });

            return response()->json($formattedEvents);
        }


        public function showDashboard()
        {
            $events = EventBooking::where('status', 'pending')->with('user')->get();

            foreach ($events as $event) {
                $conflicts = EventBooking::where('status', 'pending') // Only compare with pending events
                    ->where('room', $event->room) // Same room
                    ->where('booking_date', $event->booking_date) // Same date
                    ->where('id', '!=', $event->id) // Exclude the current event
                    ->where(function ($query) use ($event) {
                        $query->whereBetween('start_time', [$event->start_time, $event->end_time])
                            ->orWhereBetween('end_time', [$event->start_time, $event->end_time])
                            ->orWhere(function ($query) use ($event) {
                                $query->where('start_time', '<=', $event->start_time)
                                    ->where('end_time', '>=', $event->end_time);
                            });
                    })
                    ->exists();

                $event->is_conflict = $conflicts; // Add a flag for conflicting events
            }

            return view('dashboardkonfirmasi', [
                'title' => 'Dashboard Konfirmasi',
                'events' => $events,
            ]);
        }
    
    public function bulkUpdate(Request $request)
    {
        $statuses = $request->input('statuses', []);

        foreach ($statuses as $eventId => $status) {
            EventBooking::where('id', $eventId)->update(['status' => $status]);
        }

        // Cek apakah request berasal dari API atau web
        if ($request->expectsJson()) {
        return response()->json(['message' => 'Statuses updated successfully.'], 200);
        }

        return redirect()->route('dashboard.konfirmasi')->with('success', 'Statuses updated successfully.');
    }

    public function showBookingHistory()
    {
        $bookings = EventBooking::all(); // Fetch all bookings
        return view('booking_history', compact('bookings'));
    }

    public function showAcceptedEventsForToday()
    {
        $today = \Carbon\Carbon::now()->toDateString();
        $acceptedBookings = EventBooking::where('status', 'accepted')->whereDate('booking_date', '>=', $today)->get(); // Fetch only accepted bookings for today
        return view('accepted_events', compact('acceptedBookings'));
    }

    public function deleteBooking(Request $request, $id)
    {
        $booking = EventBooking::find($id);

        if (!$booking) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Booking not found.'], 404)
                : redirect()->route('accepted.events')->with('error', 'Booking not found.');
        }

        // Pastikan hanya admin yang bisa
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthorized'], 403)
                : redirect()->route('accepted.events')->with('error', 'Unauthorized action.');
        }

        // Copy data ke tabel cancelled_event_bookings
        CancelledEventBooking::create($booking->toArray());

        // Hapus dari tabel event_bookings
        $booking->delete();

        return $request->expectsJson()
            ? response()->json(['message' => 'Booking deleted and moved to cancelled table.'], 200)
            : redirect()->route('accepted.events')->with('success', 'Booking cancelled and moved.');
    }

    public function showUserBookings()
    {
        // Get the authenticated user's bookings, sorted by booking_date
        $user = Auth::user();
        $bookings = EventBooking::where('user_id', $user->id)
            ->orderBy('booking_date', 'desc') 
            ->get();

        return view('user_bookings', [
            'title' => 'My Bookings',
            'bookings' => $bookings,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $event = EventBooking::findOrFail($id);

        if ($request->input('status') === 'cancelled') {
            // Pindahkan ke tabel cancelled
            CancelledEventBooking::create($event->toArray());

            // Hapus dari tabel event_bookings
            $event->delete();
        } else {
            $event->status = $request->input('status');
            $event->save();
        }

        return redirect()->back()->with('success', 'Event status updated successfully.');
    }


    // Cancellation by user
    public function cancelByUser($id)
    {
        $booking = EventBooking::findOrFail($id);

        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Simpan data ke tabel cancelled
        CancelledEventBooking::create($booking->toArray());

        // Hapus dari event_bookings
        $booking->delete();

        return redirect()->back()->with('success', 'Booking berhasil dibatalkan.');
    }

    public function showCancelledBookings()
    {
        $cancelled = CancelledEventBooking::orderBy('booking_date', 'desc')->get();
        return view('cancelled_bookings', compact('cancelled'));
    }


}

