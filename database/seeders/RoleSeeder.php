<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = array(
            array('name' => "Admin",'guard_name'=>'web'),
            array('name' => "Student",'guard_name'=>'web'),
            array('name' => "Teacher",'guard_name'=>'web'),
        );
        DB::table('roles')->insert($roles);
    }
}
