<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSortOrderToTodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('todos', function (Blueprint $table) {
        $table->bigInteger('sort_order')->default(0)->comment('並び順')->after('reminder');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('todos', function (Blueprint $table) {
        $table->dropColumn('sort_order');
      });
    }
}
