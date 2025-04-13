<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'role' => 'Accountant',
            'first_name' => 'Accountant',
            'last_name' => 'Test',
            'email' => 'accountant@gmail.com',
            'phone_number' => '09197707864',
            'status' => 'Inactive',
            'password' => Hash::make('123456'),
        ]);
    }
}

