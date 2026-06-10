<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keanggotaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kepengurusan_id')->constrained('kepengurusan')->cascadeOnDelete();
            $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $table->foreignId('departemen_id')->nullable()->constrained('departemen')->nullOnDelete();
            $table->foreignId('jabatan_id')->constrained('jabatan');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->date('tanggal_bergabung')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Seorang anggota hanya bisa punya 1 jabatan di 1 departemen per kepengurusan
            $table->unique(['kepengurusan_id', 'anggota_id', 'departemen_id'], 'keanggotaan_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keanggotaan');
    }
};
