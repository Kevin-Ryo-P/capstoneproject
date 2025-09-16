<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Room::create(['name' => 'A101']);
        Room::create(['name' => 'A102']);
        Room::create(['name' => 'A201']);
        Room::create(['name' => 'A202']);
    }
}
