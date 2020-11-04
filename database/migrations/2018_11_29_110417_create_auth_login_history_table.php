<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAuthLoginHistoryTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('auth_login_history', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->bigInteger('user_id')->unsigned()->nullable()->index('user_id');
            $table->string('ip', 20)->nullable()->index('ip');
            $table->text('ips')->nullable();
            $table->string('host')->nullable();
            $table->string('user_agent')->nullable();
            $table->text('meta')->nullable();
            $table->string('os', 50)->nullable();
            $table->string('brand')->nullable();
            $table->string('device', 50)->nullable();
            $table->string('browser', 50)->nullable();
            $table->string('country_code', 10)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->timestamps();
            $table->boolean('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('auth_login_history');
    }
}
