<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TodosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('todos')->truncate();
      DB::statement('SET FOREIGN_KEY_CHECKS=0;');

      DB::table('todos')->insert([
        'user_id' => 1,
        'title' => 'あああ',
        'content' => 'ああああああああああああああああ',
        'state' => 0,
        'book_mark' => 0,
        'reminder' => null,
        'is_deleted' => 0,
        'created_at' => '2022-03-05 10:03:11',
        'updated_at' => '2022-03-05 10:03:11',
      ]);
      DB::table('todos')->insert([
        'user_id' => 1,
        'title' => 'いいい',
        'content' => 'いいいいいいいいいいいいい',
        'state' => 1,
        'book_mark' => 1,
        'reminder' => null,
        'is_deleted' => 1,
        'created_at' => '2022-03-05 10:03:11',
        'updated_at' => '2022-03-05 10:03:11'
      ]);
      DB::table('todos')->insert([
        'user_id' => 2,
        'title' => 'ううう',
        'content' => 'うううううううううううううう',
        'state' => 3,
        'book_mark' => 0,
        'reminder' => null,
        'is_deleted' => 0,
        'created_at' => '2022-03-05 10:03:11',
        'updated_at' => '2022-03-05 10:03:11'
      ]);

      DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
