<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // <-- PENTING: Import model User
use Illuminate\Support\Facades\Hash; // <-- PENTING: Import Hash

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat User Admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'), // Ganti 'password' ini jika Anda mau
            'base_salary' => 5000000 // Gaji contoh
        ]);

        // Beri peran 'Admin' ke user tersebut
        $admin->assignRole('Admin');
    }
}
