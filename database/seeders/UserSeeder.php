<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Omar',
            'email' => 'omar@laravel-chat.test',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Ahmed',
            'email' => 'ahmed@laravel-chat.test',
            'password' => Hash::make('password'),
        ]);
    }
}
