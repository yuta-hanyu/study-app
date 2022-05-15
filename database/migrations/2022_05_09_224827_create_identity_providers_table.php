<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdentityProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('identity_providers', function (Blueprint $table) {
        $table->unsignedBigInteger('user_id');
        $table->foreign('user_id')->references('id')->on('users'); // 外部キー制約
        $table->string('provider_id')->comment('SNS_種別番号');
        $table->string('provider_name')->comment('SNS_名前');
        $table->primary(['provider_name', 'provider_id']); // 複合キー
        $table->unique(['user_id', 'provider_name']); // ユニーク制限
        $table->softDeletes();
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
      Schema::dropIfExists('identity_providers');
    }
}
