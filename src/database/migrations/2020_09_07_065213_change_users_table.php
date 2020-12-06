<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable()->change();
        });

        // down() で空文字に変換した password を NULL に戻している
        DB::statement('UPDATE `users` SET `password` = NULL WHERE `password` = ""');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // すでに NULL が登録されている場合に、下記のエラーが発生する為、NULL を空文字に変換
        //   SQLSTATE[22004]: Null value not allowed: 1138 Invalid use of NULL value
        //   (SQL: ALTER TABLE users CHANGE password password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`)
        DB::statement('UPDATE `users` SET `password` = "" WHERE `password` IS NULL');

        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable(false)->change();
        });
    }
}
