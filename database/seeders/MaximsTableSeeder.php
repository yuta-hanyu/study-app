<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MaximsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('maxims')->truncate();
      DB::statement('SET FOREIGN_KEY_CHECKS=0;');

      DB::table('maxims')->insert([
        'maxim' => '髪が後退しているんじゃないんです。私が前進しているんです',
        'author' => '孫正義',
        'deleted_at' => null,
        'created_at' => Carbon::now(),
        'updated_at' => null,
      ]);
      DB::table('maxims')->insert([
        'maxim' => '東京の夜景は残業で出来ている',
        'author' => '',
        'deleted_at' => null,
        'created_at' => Carbon::now(),
        'updated_at' => null,
      ]);
      DB::table('maxims')->insert([
        'maxim' => 'ポジティブって迷惑って大竹（しのぶ）さんに言われた',
        'author' => '明石家さんま',
        'deleted_at' => null,
        'created_at' => Carbon::now(),
        'updated_at' => null,
      ]);
      DB::table('maxims')->insert([
        'maxim' => '努力した人が成功するとは限らない。しかし、成功したものはすべからく努力している',
        'author' => '鴨川源二',
        'deleted_at' => null,
        'created_at' => Carbon::now(),
        'updated_at' => null,
      ]);
      DB::table('maxims')->insert([
        'maxim' => 'みんなデブに豚、豚と言うけれど、豚をしっかり見てみなさい。ほら、そこまで太っていないでしょう？',
        'author' => 'ほぼさとぅー',
        'deleted_at' => null,
        'created_at' => Carbon::now(),
        'updated_at' => null,
      ]);
      DB::table('maxims')->insert([
        'maxim' => '0点だって皆で取れば平均点だ',
        'author' => '',
        'deleted_at' => null,
        'created_at' => Carbon::now(),
        'updated_at' => null,
      ]);
      // DB::table('maxims')->insert([
      //   'maxim' => '',
      //   'author' => '',
      //   'deleted_at' => null,
      //   'created_at' => Carbon::now(),
      //   'updated_at' => null,
      // ]);

      DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}
