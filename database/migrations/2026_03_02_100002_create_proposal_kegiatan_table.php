<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_kerja_id')->constrained('program_kerja')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->comment('pengaju / pengurus');
            $table->string('judul');
            $table->string('file_proposal'); // PDF path
            $table->text('catatan_pengaju')->nullable();
            $table->enum('status', [
                'diajukan',
                'review_pembina',
                'revisi_pembina',
                'review_kaprodi',
                'revisi_kaprodi',
                'disetujui',
                'ditolak',
            ])->default('diajukan');
            $table->timestamps();
        });

        Schema::create('proposal_review', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_kegiatan_id')->constrained('proposal_kegiatan')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->comment('reviewer');
            $table->enum('aksi', ['komentar', 'revisi', 'approve', 'tolak']);
            $table->text('komentar')->nullable();
            $table->string('file_lampiran')->nullable(); // re-upload / lampiran revisi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_review');
        Schema::dropIfExists('proposal_kegiatan');
    }
};
