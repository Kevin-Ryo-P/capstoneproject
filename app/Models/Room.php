<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\EventBooking;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location', 'capacity', 'description', 'blueprint', 'image'];

    // Relasi ke EventBooking
    public function bookings(): HasMany
    {
        return $this->hasMany(EventBooking::class, 'room_id');
    }

        public function scopeAvailableRooms($query)
    {
        return $query->whereDoesntHave('bookings');
    }

    public static function availableRooms()
    {
        return self::whereDoesntHave('bookings');
    }    

} 