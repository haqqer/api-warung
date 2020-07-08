<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            [
                'full_name' => 'super admin',
                'username' => 'admin',
                'email' => 'admin@email.com',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );
        DB::table('users')->insert(
            [
                'full_name' => 'pedagang',
                'username' => 'pedagang',
                'email' => 'pedagang@email.com',
                'role' => 'user',
                'password' => Hash::make('pedagan123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );
        // DB::table('users')->insert(
        //     [
        //         'full_name' => 'petugas proyek',
        //         'username' => 'petugas',
        //         'email' => 'petugas@email.com',
        //         'role' => 'petugas',
        //         'password' => Hash::make('petugas123'),
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()                
        //     ]
        // );
    }
}
