<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\EventBooking;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_show_an_event()
    {
        // Buat event di database
        $event = EventBooking::factory()->create();

        // Simulasikan request ke endpoint show event
        $response = $this->get(route('events.show', ['id' => $event->id]));

        // Pastikan response sukses dan memiliki data event yang sesuai
        $response->assertStatus(200);
        $response->assertViewIs('events.show');
        $response->assertViewHas('event', function ($viewEvent) use ($event) {
            return $viewEvent->id === $event->id;
        });
    }

    /** @test */
    public function it_returns_404_if_event_not_found()
    {
        // Simulasikan request ke event yang tidak ada
        $response = $this->get(route('events.show', ['id' => 999]));

        // Pastikan response memberikan status 404
        $response->assertStatus(404);
    }
}
