<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('event_bookings', function (Blueprint $table) {
            $table->string('location')->nullable()->after('room'); // Add 'location' column
        });
    }
    
    public function down()
    {
        Schema::table('event_bookings', function (Blueprint $table) {
            $table->dropColumn('location'); // Remove 'location' column
        });
    }
    
};
