<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookMarkFolderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::select("set foreign_key_checks = 0");
      DB::table('book_mark_folders')->truncate();

      DB::table('book_mark_folders')->insert([
        'user_id' => 1,
        'title' => 'サンプル１',
        'color' => '#C5C0C5',
        'created_at' => '2022-03-05 10:03:11',
        'updated_at' => '2022-03-05 10:03:11'
      ]);
      DB::table('book_mark_folders')->insert([
        'user_id' => 1,
        'title' => 'サンプル２',
        'color' => 'red',
        'created_at' => '2022-03-05 10:03:11',
        'updated_at' => '2022-03-05 10:03:11'
      ]);
      DB::table('book_mark_folders')->insert([
        'user_id' => 2,
        'title' => 'サンプル３',
        'color' => '#C5C0C5',
        'created_at' => '2022-03-05 10:03:11',
        'updated_at' => '2022-03-05 10:03:11'
      ]);

      DB::select("set foreign_key_checks = 1");
    }
}
