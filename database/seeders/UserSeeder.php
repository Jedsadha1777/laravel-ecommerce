<?php

namespace Database\Seeders;

use App\Domains\Shop\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'John Doe',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'phone' => '0812345678',
            'address' => '123 Main St, Bangkok',
        ]);
    }
}