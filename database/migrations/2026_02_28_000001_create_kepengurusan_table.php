<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kepengurusan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');                          // e.g. "BEM SISFOR 2025/2026"
            $table->string('periode');                       // e.g. "2025/2026"
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(false);    // hanya 1 yang aktif
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kepengurusan');
    }
};
