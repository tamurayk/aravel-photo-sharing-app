<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // FIXME: Illuminate\Database\QueryException  : SQLSTATE[22004]: Null value not allowed: 1138 Invalid use of NULL value (SQL: ALTER TABLE users CHANGE password password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`)
//        Schema::table('users', function (Blueprint $table) {
//            $table->string('password')->nullable(false)->change();
//        });
    }
}
