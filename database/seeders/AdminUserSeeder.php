<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(['username' => 'admin'], [
            'name' => 'Administrador Amaranta',
            'email' => 'admin@amaranta.local',
            'password' => Hash::make(env('ADMIN_PASSWORD', 'admin1234')),
        ]);
    }
}
