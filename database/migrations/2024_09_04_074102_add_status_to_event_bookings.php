<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddStatusToEventBookings extends Migration
{
    public function up()
    {
        Schema::table('event_bookings', function (Blueprint $table) {
            // Cek dulu kalau kolomnya belum ada, baru tambahkan
            if (!Schema::hasColumn('event_bookings', 'status')) {
                $table->string('status')->default('diajukan'); // Sesuaikan default dengan istilah lo
            }
        });
    }

    public function down()
    {
        Schema::table('event_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('event_bookings', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
}
