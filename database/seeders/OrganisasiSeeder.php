<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Departemen;
use App\Models\Jabatan;
use App\Models\Keanggotaan;
use App\Models\Kepengurusan;
use Illuminate\Database\Seeder;

class OrganisasiSeeder extends Seeder
{
    public function run(): void
    {
        /* ========================================
           Jabatan (global — berlaku semua periode)
           ======================================== */
        $jabatanData = [
            ['nama' => 'Ketua Umum',             'level' => 1, 'deskripsi' => 'Pimpinan tertinggi organisasi'],
            ['nama' => 'Wakil Ketua',             'level' => 2, 'deskripsi' => 'Wakil pimpinan organisasi'],
            ['nama' => 'Sekretaris Umum',         'level' => 3, 'deskripsi' => 'Pengelola administrasi organisasi'],
            ['nama' => 'Bendahara Umum',          'level' => 4, 'deskripsi' => 'Pengelola keuangan organisasi'],
            ['nama' => 'Ketua Departemen',        'level' => 5, 'deskripsi' => 'Pimpinan departemen'],
            ['nama' => 'Wakil Ketua Departemen',  'level' => 6, 'deskripsi' => 'Wakil pimpinan departemen'],
            ['nama' => 'Sekretaris Departemen',   'level' => 7, 'deskripsi' => 'Sekretaris departemen'],
            ['nama' => 'Anggota',                 'level' => 10, 'deskripsi' => 'Anggota biasa'],
        ];

        $jabatan = collect();
        foreach ($jabatanData as $j) {
            $jabatan->push(Jabatan::create($j));
        }

        /* ========================================
           Kepengurusan
           ======================================== */
        $kepengurusan2024 = Kepengurusan::create([
            'nama' => 'BEM SISFOR 2024/2025',
            'periode' => '2024/2025',
            'tanggal_mulai' => '2024-01-15',
            'tanggal_selesai' => '2025-01-14',
            'visi' => 'Menjadi organisasi mahasiswa yang inovatif, kolaboratif, dan berdampak nyata bagi lingkungan kampus.',
            'misi' => "1. Mengembangkan program kerja yang relevan dengan kebutuhan mahasiswa\n2. Meningkatkan kolaborasi antar departemen\n3. Membangun budaya organisasi yang inklusif dan profesional\n4. Mendorong inovasi dalam setiap kegiatan",
            'deskripsi' => 'Kepengurusan periode 2024/2025 dengan fokus pada inovasi dan kolaborasi.',
            'is_active' => false,
        ]);

        $kepengurusan2025 = Kepengurusan::create([
            'nama' => 'BEM SISFOR 2025/2026',
            'periode' => '2025/2026',
            'tanggal_mulai' => '2025-01-15',
            'tanggal_selesai' => '2026-01-14',
            'visi' => 'Mewujudkan organisasi yang adaptif, transparan, dan membina potensi mahasiswa secara optimal.',
            'misi' => "1. Menyelenggarakan kegiatan yang meningkatkan soft skill mahasiswa\n2. Transparansi dalam pengelolaan organisasi\n3. Memperkuat jaringan alumni dan stakeholder\n4. Optimalisasi teknologi dalam kegiatan organisasi",
            'deskripsi' => 'Kepengurusan periode aktif 2025/2026.',
            'is_active' => true,
        ]);

        /* ========================================
           Departemen (per kepengurusan aktif)
           ======================================== */
        $deptData = [
            ['nama' => 'Akademik',              'singkatan' => 'AKAD'],
            ['nama' => 'Humas & Kominfo',       'singkatan' => 'HUKOM'],
            ['nama' => 'Kewirausahaan',         'singkatan' => 'KWU'],
            ['nama' => 'Minat & Bakat',          'singkatan' => 'MINBA'],
            ['nama' => 'Sosial & Masyarakat',   'singkatan' => 'SOSMAS'],
        ];

        $departemen = collect();
        foreach ($deptData as $d) {
            $departemen->push(Departemen::create(array_merge($d, [
                'kepengurusan_id' => $kepengurusan2025->id,
            ])));
        }

        // Also add departments to old period
        foreach ($deptData as $d) {
            Departemen::create(array_merge($d, [
                'kepengurusan_id' => $kepengurusan2024->id,
            ]));
        }

        /* ========================================
           Anggota & Keanggotaan
           ======================================== */
        $namaLaki = [
            'Ahmad Fauzan', 'Rizky Pratama', 'Muhammad Ilham', 'Dimas Ardiansyah',
            'Fajar Nugroho', 'Budi Santoso', 'Yoga Aditya', 'Rendra Wijaya',
            'Hadi Saputra', 'Arif Rahman', 'Bayu Setiawan', 'Galang Firmansyah',
        ];
        $namaPerempuan = [
            'Siti Nurhaliza', 'Anisa Rahma', 'Putri Wulandari', 'Dewi Safitri',
            'Nabila Zahira', 'Rina Kartika', 'Maya Anggraini', 'Dina Permata',
            'Lestari Utami', 'Indah Purnama', 'Fitri Handayani', 'Aulia Salsabila',
        ];
        $prodiList = [
            'Sistem Informasi', 'Teknik Informatika', 'Sistem Informasi',
            'Teknik Informatika', 'Sistem Informasi',
        ];

        $allAnggota = collect();
        $nimCounter = 1;

        // Create male members
        foreach ($namaLaki as $nama) {
            $allAnggota->push(Anggota::create([
                'nama' => $nama,
                'nim' => '2023'.str_pad($nimCounter++, 4, '0', STR_PAD_LEFT),
                'email' => strtolower(str_replace(' ', '.', $nama)).'@student.ac.id',
                'no_hp' => '08'.rand(1000000000, 9999999999),
                'jenis_kelamin' => 'L',
                'angkatan' => (string) rand(2021, 2024),
                'prodi' => $prodiList[array_rand($prodiList)],
            ]));
        }

        // Create female members
        foreach ($namaPerempuan as $nama) {
            $allAnggota->push(Anggota::create([
                'nama' => $nama,
                'nim' => '2023'.str_pad($nimCounter++, 4, '0', STR_PAD_LEFT),
                'email' => strtolower(str_replace(' ', '.', $nama)).'@student.ac.id',
                'no_hp' => '08'.rand(1000000000, 9999999999),
                'jenis_kelamin' => 'P',
                'angkatan' => (string) rand(2021, 2024),
                'prodi' => $prodiList[array_rand($prodiList)],
            ]));
        }

        /* ========================================
           Keanggotaan — assign to active kepengurusan
           ======================================== */

        // BPH (Badan Pengurus Harian) — no department
        $ketuaUmum = $jabatan->where('nama', 'Ketua Umum')->first();
        $wakilKetua = $jabatan->where('nama', 'Wakil Ketua')->first();
        $sekretaris = $jabatan->where('nama', 'Sekretaris Umum')->first();
        $bendahara = $jabatan->where('nama', 'Bendahara Umum')->first();
        $ketuaDept = $jabatan->where('nama', 'Ketua Departemen')->first();
        $anggotaJabat = $jabatan->where('nama', 'Anggota')->first();

        $idx = 0;

        // Ketua Umum
        Keanggotaan::create([
            'kepengurusan_id' => $kepengurusan2025->id,
            'anggota_id' => $allAnggota[$idx++]->id,
            'jabatan_id' => $ketuaUmum->id,
            'departemen_id' => null,
            'status' => 'aktif',
            'tanggal_bergabung' => '2025-01-15',
        ]);

        // Wakil Ketua
        Keanggotaan::create([
            'kepengurusan_id' => $kepengurusan2025->id,
            'anggota_id' => $allAnggota[$idx++]->id,
            'jabatan_id' => $wakilKetua->id,
            'departemen_id' => null,
            'status' => 'aktif',
            'tanggal_bergabung' => '2025-01-15',
        ]);

        // Sekretaris
        Keanggotaan::create([
            'kepengurusan_id' => $kepengurusan2025->id,
            'anggota_id' => $allAnggota[$idx++]->id,
            'jabatan_id' => $sekretaris->id,
            'departemen_id' => null,
            'status' => 'aktif',
            'tanggal_bergabung' => '2025-01-15',
        ]);

        // Bendahara
        Keanggotaan::create([
            'kepengurusan_id' => $kepengurusan2025->id,
            'anggota_id' => $allAnggota[$idx++]->id,
            'jabatan_id' => $bendahara->id,
            'departemen_id' => null,
            'status' => 'aktif',
            'tanggal_bergabung' => '2025-01-15',
        ]);

        // Assign Ketua Departemen to each department
        foreach ($departemen as $dept) {
            Keanggotaan::create([
                'kepengurusan_id' => $kepengurusan2025->id,
                'anggota_id' => $allAnggota[$idx++]->id,
                'jabatan_id' => $ketuaDept->id,
                'departemen_id' => $dept->id,
                'status' => 'aktif',
                'tanggal_bergabung' => '2025-01-15',
            ]);
        }

        // Remaining members as Anggota distributed across departments
        while ($idx < $allAnggota->count()) {
            $dept = $departemen[$idx % $departemen->count()];
            Keanggotaan::create([
                'kepengurusan_id' => $kepengurusan2025->id,
                'anggota_id' => $allAnggota[$idx++]->id,
                'jabatan_id' => $anggotaJabat->id,
                'departemen_id' => $dept->id,
                'status' => 'aktif',
                'tanggal_bergabung' => '2025-01-15',
            ]);
        }
    }
}
