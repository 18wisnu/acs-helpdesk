<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'owner', 'staff'])->default('staff')->after('email');
            $table->foreignId('site_id')->nullable()->after('role')->constrained('sites')->onDelete('set null');
        });

        Schema::table('devices', function (Blueprint $table) {
            $table->foreignId('site_id')->nullable()->after('customer_id')->constrained('sites')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->dropColumn('site_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->dropColumn(['role', 'site_id']);
        });
    }
};
