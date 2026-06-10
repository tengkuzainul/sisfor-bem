<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_proker', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('warna', 7)->default('#3b82f6'); // hex color for calendar
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_proker');
    }
};
