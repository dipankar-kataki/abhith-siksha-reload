<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user=User::create([
            'name' => 'Admin',
            'email' => 'admin@abhit.com',
            'phone' => '7896541230',
            'otp' => '123456',
            'verify_otp' => 1,
            'password' => Hash::make('123456') ,
            'type_id' => 1,
            'is_activate' => 1
        ]);
        $roles = 1;
        $user->assignRole($roles);
    }
}
