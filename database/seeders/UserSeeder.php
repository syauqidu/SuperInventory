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
        User::create([
            'name' => 'Admin BizGrow',
            'email' => 'admin@bizgrow.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Staff account
        User::create([
            'name' => 'Staff Gudang',
            'email' => 'staff@bizgrow.com',
            'password' => Hash::make('staff123'),
            'role' => 'staff',
        ]);
    }
}
