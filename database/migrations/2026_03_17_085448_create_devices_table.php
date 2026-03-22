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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('genieacs_id')->unique();
            $table->string('brand')->nullable(); // Ini nanti diisi dari VP Model
            $table->string('sn')->nullable();    // Tambah kolom Serial Number
            $table->string('ip_tr069')->nullable(); // Tambah kolom IP TR069
            $table->string('ip_pppoe')->nullable(); // Tambah kolom IP PPPOE
            $table->string('ssid')->nullable();
            $table->string('active_devices')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
