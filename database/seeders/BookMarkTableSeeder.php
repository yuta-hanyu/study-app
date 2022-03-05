<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookMarkTableSeeder extends Seeder
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

      DB::table('book_marks')->insert([
        'user_id' => 1,
        'book_mark_folders_id' => 1,
        'title' => 'サンプル１',
        'link' => 'https://vuetifyjs.com/ja/components/color-pickers/#section-30b930a630a930c330c1',
        'memo' => 'メモサンプル１',
        'created_at' => '2022-03-05 10:03:11',
        'updated_at' => '2022-03-05 10:03:11'
      ]);
      DB::table('book_marks')->insert([
        'user_id' => 1,
        'book_mark_folders_id' => 2,
        'title' => 'サンプル２',
        'link' => 'https://qiita.com/Otake_M/items/3c761e1a5e65b04c6c0e',
        'memo' => 'メモサンプル２',
        'created_at' => '2022-03-05 10:03:11',
        'updated_at' => '2022-03-05 10:03:11'
      ]);
      DB::table('book_marks')->insert([
        'user_id' => 2,
        'book_mark_folders_id' => 1,
        'title' => 'サンプル３',
        'link' => 'https://hara-chan.com/it/programming/laravel-migrate-commands/',
        'memo' => 'メモサンプル３',
        'created_at' => '2022-03-05 10:03:11',
        'updated_at' => '2022-03-05 10:03:11'
      ]);

      DB::select("set foreign_key_checks = 1");
    }
}
