<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable(false)->comment('ユーザーID');
            $table->string('title')->nullable(false)->comment('タイトル');
            $table->string('content',2000)->nullable(true)->comment('コンテンツ');
            $table->tinyInteger('state')->nullable(false)->comment('状態');
            $table->tinyInteger('book_mark')->nullable(true)->default(0)->comment('ブックマークフラグ');
            $table->dateTime('reminder')->nullable(true)->comment('リマインダー');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
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
        Schema::dropIfExists('todos');
    }
}
