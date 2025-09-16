<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    /**
     * Display a list of rooms.
     */
    public function index()
    {
        $rooms = Room::all(); // Fetch all rooms
        return view('rooms.index', compact('rooms'));
    }

    /**
     * Store a new room.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:rooms,name',
            'location' => 'nullable|string',
            'capacity' => 'nullable|integer|min:1',
            'blueprint' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['name', 'location', 'capacity']);

        // Handle blueprint upload
        if ($request->hasFile('blueprint')) {
            $data['blueprint'] = $request->file('blueprint')->store('blueprints', 'public');
        }

        // Handle room image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('room_images', 'public');
        }

        Room::create($data);

        return redirect()->route('rooms.index')->with('success', 'Room added successfully!');
    }

    
    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    /**
     * Update the specified room in storage.
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required|unique:rooms,name,' . $room->id,
            'location' => 'nullable|string',
            'capacity' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'blueprint' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        $data = $request->only(['name', 'location', 'capacity', 'description']);
    
        // Handle blueprint upload
        if ($request->hasFile('blueprint')) {
            if ($room->blueprint && Storage::exists('public/' . $room->blueprint)) {
                Storage::delete('public/' . $room->blueprint);
            }
            $data['blueprint'] = $request->file('blueprint')->store('blueprints', 'public');
        }
    
        // Handle image upload
        if ($request->hasFile('image')) {
            if ($room->image && Storage::exists('public/' . $room->image)) {
                Storage::delete('public/' . $room->image);
            }
            $data['image'] = $request->file('image')->store('room_images', 'public');
        }
    
        $room->update($data);
    
        return redirect()->route('rooms.index')->with('success', 'Room updated successfully!');
    }
    

    public function listRooms()
    {
        $rooms = Room::all(); // Fetch all rooms
        return view('rooms.list', compact('rooms'));
    }

    public function show(Room $room)
    {
        return view('rooms.show', compact('room'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        // Search for a room by name
        $room = Room::where('name', 'like', '%' . $query . '%')->first();

        if ($room) {
            // Redirect to the room details page
            return redirect()->route('rooms.show', $room->id);
        }

        // Redirect back with an error message if no match is found
        return redirect()->back()->with('error', 'Room not found.');
    }

    public function getRoomDetails($name)
    {
        $room = Room::where('name', $name)->first();

        if (!$room) {
            return response()->json(['message' => 'Room not found'], 404);
        }

        return response()->json($room);
    }

    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully!');
    }

}
