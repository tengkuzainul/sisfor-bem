<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------
        | Rekrutmen — Periode open-recruitment
        |--------------------------------------------------------------
        */
        Schema::create('rekrutmen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kepengurusan_id')->constrained('kepengurusan')->cascadeOnDelete();
            $table->string('judul');                                    // e.g. "Open Recruitment BEM Sisfor 2026"
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->text('persyaratan')->nullable();                    // requirement list
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir');
            $table->string('poster')->nullable();                      // poster image
            $table->enum('status', ['draft', 'dibuka', 'ditutup', 'selesai'])->default('draft');
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------
        | Pendaftar — Calon anggota yang mendaftar
        |--------------------------------------------------------------
        */
        Schema::create('pendaftar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekrutmen_id')->constrained('rekrutmen')->cascadeOnDelete();
            $table->string('kode_pendaftaran')->unique();              // auto-generated REG-XXXXXX

            // Step 1: Data Pribadi
            $table->string('nama_lengkap');
            $table->string('nim');
            $table->string('email');
            $table->string('no_hp');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('prodi');                                   // Program Studi
            $table->string('angkatan');                                // e.g. "2024"
            $table->text('alamat')->nullable();
            $table->string('foto')->nullable();                        // pas foto upload

            // Step 2: Pilihan & Motivasi
            $table->foreignId('departemen_pilihan_1')->nullable()->constrained('departemen')->nullOnDelete();
            $table->foreignId('departemen_pilihan_2')->nullable()->constrained('departemen')->nullOnDelete();
            $table->text('motivasi');                                   // alasan ingin bergabung
            $table->text('pengalaman_organisasi')->nullable();
            $table->text('keahlian')->nullable();                      // skill / kemampuan

            // Step 3: Dokumen
            $table->string('cv_file')->nullable();                     // PDF
            $table->string('sertifikat_file')->nullable();             // PDF/image
            $table->string('link_portfolio')->nullable();              // URL

            // Status & Admin
            $table->enum('status', ['mendaftar', 'review', 'wawancara', 'diterima', 'ditolak', 'cadangan'])->default('mendaftar');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------
        | Pendaftar Review — Masukan dari internal per departemen
        |--------------------------------------------------------------
        */
        Schema::create('pendaftar_review', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained('pendaftar')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();     // reviewer
            $table->foreignId('departemen_id')->nullable()->constrained('departemen')->nullOnDelete();
            $table->enum('tipe', ['saran', 'kritik', 'rekomendasi']);
            $table->text('komentar');
            $table->enum('rekomendasi_status', ['direkomendasikan', 'tidak_direkomendasikan', 'netral'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftar_review');
        Schema::dropIfExists('pendaftar');
        Schema::dropIfExists('rekrutmen');
    }
};
