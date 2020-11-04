<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add2faToUsers extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table(config('bright.table.users', 'users'), function (Blueprint $table) {
            $table->string('google2fa_secret', 50)->nullable()->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table(config('bright.table.users', 'users'), function (Blueprint $table) {
            $table->dropColumn('google2fa_secret');
        });
    }
}
