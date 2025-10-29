<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin account untuk testing
        User::create([
            'name' => 'Admin SuperInventory',
            'email' => 'admin@superinventory.com',
            'password' => Hash::make('password123'), // Sesuai info di halaman login
            'role' => 'admin',
        ]);

        // Staff account untuk testing
        User::create([
            'name' => 'Staff Gudang',
            'email' => 'staff@superinventory.com',
            'password' => Hash::make('password123'), // Sesuai info di halaman login
            'role' => 'staff',
        ]);

        // Demo accounts untuk tim developer
        User::create([
            'name' => 'Nijar',
            'email' => 'nijar@superinventory.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Kalel',
            'email' => 'kalel@superinventory.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
    }
}
