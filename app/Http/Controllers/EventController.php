<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventBooking; // Import the EventBooking model

class EventController extends Controller
{
    public function show($id)
    {
        $event = EventBooking::findOrFail($id);  // Fetch the event booking by its ID
        return view('events.show', compact('event'));  // Return the simple event details page
    }
}