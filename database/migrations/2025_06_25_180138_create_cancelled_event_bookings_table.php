<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCancelledEventBookingsTable extends Migration
{
    public function up()
    {
        Schema::create('cancelled_event_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('room');
            $table->unsignedBigInteger('room_id')->nullable();
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('event_type');
            $table->string('event_name');
            $table->text('description')->nullable();
            $table->string('status')->default('cancelled');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('role')->nullable();
            $table->string('location')->nullable();
            $table->string('permit_picture')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cancelled_event_bookings');
    }
}
