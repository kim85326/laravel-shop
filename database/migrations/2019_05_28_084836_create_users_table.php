<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 150);
            $table->string('password', 60);
            // 帳號型態
            // - A (Admin)：管理者
            // - G (General)：一般會員
            $table->string('type', 1)->default('G');
            $table->string('nickname', 50);
            $table->timestamps();

            // 索引
            $table->unique(['email'], 'user_mail_uk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
