<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kontak_pesan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('instansi')->nullable();
            $table->string('email');
            $table->string('subjek');
            $table->text('pesan');
            $table->boolean('dibaca')->default(false); // biar ada notif "pesan baru"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kontak_pesan');
    }
};