<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'name'    => 'Super Admin',
            'email'    => 'superadmin@fujishka.com',
            'phone_number' => '+919955887755',
            'password'   =>  Hash::make('Fujishka123'),
            'email_verified_at' => now(),
            'remember_token' =>  Str::random(10),
            'status' => 1,
            'created_by' => 1,
        ]);
    }
}
