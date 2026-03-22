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
        Schema::table('users', function (Blueprint $table) {
            // Kita ubah menjadi string biasa saja supaya bebas isi apa saja ke depannya
            $table->string('role')->default('client')->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kembalikan ke enum jika diperlukan
            $table->enum('role', ['admin', 'staff'])->default('staff')->change();
        });
    }
};
