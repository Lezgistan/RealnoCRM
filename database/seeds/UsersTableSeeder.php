<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id'=>'3',
            'name' => 'Vasya Chernykh',
            'email' => 'emcheltiiii@gmail.com',
            'email_verified_at'=>'2019-10-12',
            'password' => '123qwerty',
            'remember_token'=>'2019-10-12',
            'created_at'=>'2019-10-12',
            'updated_at'=>'2019-10-12',
            'phone_number'=>'+1111111',
        ]);
    }
}
