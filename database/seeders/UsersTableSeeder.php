<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('users')->truncate();
      DB::table('users')->insert([
        'login' => 'aaa',
        'name' => 'あああ',
        'email' => 'eee@gmail.com',
        'password' => 'aaa',
        'is_deleted' => 0,
      ]);
    }
}
