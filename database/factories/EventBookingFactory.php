<?php

namespace Database\Factories;

use App\Models\EventBooking;
use App\Models\User;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EventBookingFactory extends Factory
{
    protected $model = EventBooking::class;

    public function definition()
    {
        $room = Room::factory()->create(); // Buat room yang valid di database
        $startTime = Carbon::createFromFormat('H:i', $this->faker->time('H:i'));
        $endTime = (clone $startTime)->addHours(2); // Pastikan end_time setelah start_time

        return [
            'name' => $this->faker->name(),
            'room' => $room->name,
            'room_id' => $room->id,
            'booking_date' => $this->faker->date(),
            'start_time' => $startTime->format('H:i'),
            'end_time' => $endTime->format('H:i'),
            'event_type' => $this->faker->randomElement(['Conference', 'Workshop', 'Seminar']),
            'event_name' => $this->faker->words(2, true),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'user_id' => User::factory(), // Pastikan user dibuat dalam database
            'role' => $this->faker->randomElement(['user', 'admin']),
        ];
    }
}
