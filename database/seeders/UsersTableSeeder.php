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
      DB::select("SET FOREIGN_KEY_CHECKS = 0");
      DB::table('users')->truncate();

      DB::table('users')->insert([
        'name' => 'ゲストユーザー',
        'email' => 'guest@gmail.com',
        'password' => '$2y$10$dyh3ID9qIkYX6XuqYKjgiOPN8gZRCxokse4tSiSvaN4saK/dSBIuy',
        'is_deleted' => 0,
        'created_at' => '2022-03-05 10:03:11',
        'updated_at' => '2022-03-05 10:03:11'
      ]);
      DB::table('users')->insert([
        'name' => 'ccc',
        'email' => 'ccc@gmail.com',
        'password' => '$2y$10$8ANWoUBI.vLUL4jlMxKCEucmcE6OQgNYC/yHHG3ivG4ZBfKwa7crm',
        'is_deleted' => 0,
        'created_at' => '2022-03-05 10:03:11',
        'updated_at' => '2022-03-05 10:03:11'
      ]);
      DB::select("SET FOREIGN_KEY_CHECKS = 1");
    }
}
