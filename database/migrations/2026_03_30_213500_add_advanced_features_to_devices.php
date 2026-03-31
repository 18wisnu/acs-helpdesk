<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->string('ssid_2')->nullable()->after('ssid');
            $table->string('password_1')->nullable()->after('ssid_2');
            $table->string('password_2')->nullable()->after('password_1');
            $table->timestamp('last_inform')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn(['ssid_2', 'password_1', 'password_2', 'last_inform']);
        });
    }
};
