<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
       User::create([
    'name'      => 'Admin',
    'last_name' => 'Sistema',
    'email'     => 'admin@dental.com',
    'password'  => Hash::make('123456'),
    'role'      => 'admin',
]);

User::create([
    'name'      => 'María',
    'last_name' => 'Recepcionista',
    'email'     => 'maria@dental.com',
    'password'  => Hash::make('12345678'),
    'role'      => 'receptionist',
]);
    }
}