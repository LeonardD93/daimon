<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $userExists = User::where('name', 'root')
                          ->orWhere('email', 'root@example.com')
                          ->exists();

        if (!$userExists) {
            User::create([
                'name' => 'root',
                'email' => 'root@example.com',
                'password' => Hash::make('password'),
            ]);

            $this->command->info('User root@example.com created successfully.');
        } else {
            $this->command->info('User root@example.com already exists.');
        }
    }
}
