<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('email', 255)->nullable(false)->comment('メールアドレス');
            $table->string('token', 250)->nullable(false)->comment('確認トークン');
            $table->tinyInteger('status')->default(0)->nullable(false)->comment('ステータス');
            $table->dateTime('expiration_datetime')->nullable(false)->comment('有効期限');
            $table->tinyInteger('is_deleted')->default(0)->comment('論理削除');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_verifications');
    }
}
