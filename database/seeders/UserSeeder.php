<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'admin@email.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pak X',
                'email' => 'PakX@email.com',
                'password' => Hash::make('1234567890'),
                'role' => 'professor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kaffa',
                'email' => 'Kaffa@email.com',
                'password' => Hash::make('1234567890'),
                'role' => 'student',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'name' => 'Kevin',
                'email' => 'Kevin@email.com',
                'password' => Hash::make('1234567890'),
                'role' => 'student',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
