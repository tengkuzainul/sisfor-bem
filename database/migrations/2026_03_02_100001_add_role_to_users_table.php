<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['administrator', 'pembina', 'pengurus'])->default('pengurus')->after('email');
            $table->foreignId('anggota_id')->nullable()->after('role')->constrained('anggota')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['anggota_id']);
            $table->dropColumn(['role', 'anggota_id']);
        });
    }
};
