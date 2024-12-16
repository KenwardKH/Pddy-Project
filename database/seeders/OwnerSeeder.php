<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'email' => 'kenwardkh2@gmail.com',
            'password' => Hash::make('rahasia123'), // Ganti dengan password yang aman
            'google_id' => null, // null jika tidak menggunakan Google Login
            'role' => 'pemilik', // Peran pemilik
            'email_verified_at' => now(),
        ]);
    }
}
