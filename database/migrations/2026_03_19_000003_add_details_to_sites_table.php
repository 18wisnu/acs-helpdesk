<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Kolom sudah dibuat di migrasi create_sites_table
     */
    public function up(): void
    {
        // Kosong karena kolom sudah ada di create_sites_table
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kosong
    }
};
