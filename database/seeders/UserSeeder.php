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
            'email' => 'ind',
            'password' => Hash::make('password123'), // Sesuai info di halaman login
            'role' => 'admin',
            'approved' => true
        ]);

        // Staff account untuk testing
        User::create([
            'name' => 'Staff Gudang',
            'email' => 'staff@superinventory.com',
            'password' => Hash::make('password123'), // Sesuai info di halaman login
            'role' => 'staff',
            'approved' => true
        ]);

        // Demo accounts untuk tim developer
        User::create([
            'name' => 'Nijar',
            'email' => 'nijar@superinventory.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'approved' => true
        ]);

        User::create([
            'name' => 'Kalel',
            'email' => 'kalel@superinventory.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'approved' => true
        ]);
    }
}
