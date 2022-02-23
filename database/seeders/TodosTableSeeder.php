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
      DB::table('todos')->insert([
        'user_id' => 1,
        'title' => 'あああ',
        'content' => 'ああああああああああああああああ',
        'state' => 0,
        'book_mark' => 0,
        'is_deleted' => 0,
      ]);
      DB::table('todos')->insert([
        'user_id' => 1,
        'title' => 'いいい',
        'content' => 'いいいいいいいいいいいいい',
        'state' => 1,
        'book_mark' => 1,
        'is_deleted' => 1,
      ]);
      DB::table('todos')->insert([
        'user_id' => 2,
        'title' => 'ううう',
        'content' => 'うううううううううううううう',
        'state' => 3,
        'book_mark' => 0,
        'is_deleted' => 0,
      ]);

      // ,[
      //   'user_id' => 1,
      //   'title' => 'いいい',
      //   'content' => 'いいいいいいいいいいいいい',
      //   'state' => 0,
      //   'book_mark' => 1,
      //   'is_deleted' => 1,
      // ],[
      //   'user_id' => 1,
      //   'title' => 'ううう',
      //   'content' => 'うううううううううううううう',
      //   'state' => 3,
      //   'book_mark' => 0,
      //   'is_deleted' => 0,
      // ]);
    }
}
