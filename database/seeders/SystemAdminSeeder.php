<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SystemAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@clubhub.com',
            'password' => Hash::make('password123'),
            'role' => 'system_admin',
            'status' => 'active',
        ]);
    }
}