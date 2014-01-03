<?php

class UsersTableSeeder extends Seeder {

    public function run()
    {

        $users = [
        	['username' => 'gerwin', 'password' => md5('gerwin'), 'employeeid' => 'FM-0034-120130', 'firstname' => 'John Gerwin', 'middlename' => 'Arceta', 'lastname' => 'De las Alas', 'email' => 'gerwin.delasalas@fiametta.ph', 'role' => 1, 'is_active' => 1]
        ];
        DB::table('tbl_users')->insert($users);
        
    }

}