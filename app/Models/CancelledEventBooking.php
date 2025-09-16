<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelledEventBooking extends Model
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
}
