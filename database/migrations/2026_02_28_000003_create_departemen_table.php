<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departemen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kepengurusan_id')->constrained('kepengurusan')->cascadeOnDelete();
            $table->string('nama');
            $table->string('singkatan')->nullable();   // e.g. "KOMINFO"
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departemen');
    }
};
