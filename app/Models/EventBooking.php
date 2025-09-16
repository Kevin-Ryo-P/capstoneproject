<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'room',
        'room_id',
        'booking_date',
        'start_time',
        'end_time',
        'event_type',
        'event_name',
        'description',
        'status',
        'user_id',
        'name',
        'role',
        'location',
        'permit_picture',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
