<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Str;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=[
            'firstname' => Str::random(10),
            'lastname' => Str::random(10),
            'email' => 'teacher@gmail.com',
            'phone' =>8867589870,
            'otp'=>123456,
            'verify_otp'=>1,
            'type_id'=>2,
            'password' => Hash::make('123456teacher'),
            'is_activate'=>1, 
        ];
        $user=User::create($data);
        $roles = 3;
        $user->assignRole($roles);
    }
}
