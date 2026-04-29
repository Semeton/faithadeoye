<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'faithadeoye@gmail.com'],
            [
                'name' => 'Faith O. Adeoye',
                'password' => Hash::make('change-me-on-first-login'),
            ]
        );
    }
}
