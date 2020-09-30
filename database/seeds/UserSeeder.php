<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::table('users')->insert([
            'name' => 'Admin',
             'first_name' => 'Admin',
              'last_name' => '',
            'email' =>'harpreet.kaur@digimantra.com',
            'country' =>'United States',
            'status' =>'1',
            'school_id' =>'1',
            'password' => Hash::make('123456'),
            'role_id' => 1,
        ]);
    }
}
