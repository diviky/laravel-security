<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add2faToUsers extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table(config('bright.table.users', 'users'), function (Blueprint $table): void {
            $table->string('google2fa_secret', 50)->nullable()->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(config('bright.table.users', 'users'), function (Blueprint $table): void {
            $table->dropColumn('google2fa_secret');
        });
    }
}
