<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * Insert demo data Users table
     */
    public function run()
    {
        // install for customer
        $first_name = 'Younis';
        $last_name = 'Al Abri';
        $email = 'rasingn@gmail.com';
        $password ='92395921';

        DB::table('users')->insert([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'password' => Hash::make($password),
            'verified' => 1,
            'is_admin' => 1,
            'token' => '',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);
    }
}
