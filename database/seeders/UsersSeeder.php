<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'عاصم',
                'email' => 'fajeralhuriya_dev@dev.com',
                'password' => Hash::make('DarkVictor$123321'),
                'user_status' => "مسؤول",
            ],
            [
                'name' => 'همام',
                'email' => 'fajeralhuriya_admin@hammam.com',
                'password' => Hash::make('hammam$123321'),
                'user_status' => "مسؤول",
            ],
            [
                'name' => 'سامي',
                'email' => 'fajeralhuriya_admin@sami.com',
                'password' => Hash::make('sami$123321'),
                'user_status' => "مسؤول",
            ],
        ];
        DB::table('users')->insert($users);
    }
}
