<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_marks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable(false)->comment('ユーザーID');
            $table->bigInteger('book_mark_folders_id')->unsigned()->nullable(true)->comment('フォルダID');
            $table->string('title')->nullable(false)->comment('タイトル');
            $table->longText('link')->nullable(false)->comment('リンク');
            $table->string('memo',1000)->nullable(true)->comment('メモ');
            $table->tinyInteger('fixed')->default(0)->nullable(true)->comment('固定');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->cascadeOnDelete();

            $table->foreign('book_mark_folders_id')
                  ->references('id')
                  ->on('book_mark_folders')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_marks');
    }
}
