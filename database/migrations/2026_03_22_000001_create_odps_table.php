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
        Schema::create('odps', function (Blueprint $row) {
            $row->id();
            $row->string('name');
            $row->foreignId('site_id')->nullable()->constrained('sites')->onDelete('cascade');
            $row->decimal('latitude', 10, 8)->nullable();
            $row->decimal('longitude', 11, 8)->nullable();
            $row->string('address')->nullable();
            $row->integer('capacity')->default(8);
            $row->timestamps();
        });

        // Update devices table to have odp_id
        Schema::table('devices', function (Blueprint $row) {
            $row->foreignId('odp_id')->nullable()->constrained('odps')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devices', function (Blueprint $row) {
            $row->dropForeign(['odp_id']);
            $row->dropColumn('odp_id');
        });
        Schema::dropIfExists('odps');
    }
};
