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
        Schema::table('sites', function (Blueprint $table) {
            $table->decimal('pon_power', 5, 2)->default(4.00)->after('ip_address');
        });

        Schema::table('odps', function (Blueprint $table) {
            $table->string('splitter_type')->default('1:8')->after('capacity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn('pon_power');
        });

        Schema::table('odps', function (Blueprint $table) {
            $table->dropColumn('splitter_type');
        });
    }
};
