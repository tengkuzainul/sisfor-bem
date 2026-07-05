<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu Anggota BEM - {{ $anggota->nama }}</title>
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* CSS Khusus Cetak Kartu */
        body {
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
            font-family: 'Inter', system-ui, sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .print-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            margin-top: 30px;
        }

        /* Ukuran Standar Kartu CR80 (85.6mm x 54mm) */
        .member-card {
            width: 85.6mm;
            height: 54mm;
            border-radius: 4.8mm; /* Sesuai rasio sudut bulat ID card */
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            background: linear-gradient(135deg, oklch(0.379 0.146 265.522), oklch(0.282 0.091 267.935));
            border: 0.3mm solid rgba(251, 191, 36, 0.3); /* Gold border */
            color: #ffffff;
            page-break-inside: avoid;
            box-sizing: border-box;
        }

        .member-card.back-card {
            background: linear-gradient(135deg, oklch(0.282 0.091 267.935), oklch(0.208 0.042 265.755));
        }

        .card-grid {
            position: absolute;
            inset: 0;
            opacity: 0.08;
            background-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)];
            background-size: 4.5mm 4.5mm;
            pointer-events: none;
        }

        /* Kop Kartu */
        .card-header {
            height: 12mm;
            background: rgba(0, 0, 0, 0.15);
            border-bottom: 0.25mm solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 4mm;
            box-sizing: border-box;
        }

        .header-logo-text {
            display: flex;
            align-items: center;
            gap: 2mm;
        }

        .logo-img {
            height: 7mm;
            width: 7mm;
            object-fit: contain;
        }

        .header-title-container {
            line-height: 1.1;
        }

        .header-main-title {
            font-size: 2.2mm;
            font-weight: 800;
            letter-spacing: 0.15mm;
            text-transform: uppercase;
            color: #ffffff;
            margin: 0;
        }

        .header-subtitle {
            font-size: 1.7mm;
            font-weight: 500;
            color: #cbd5e1;
            margin: 0;
        }

        .card-badge {
            font-size: 1.6mm;
            font-weight: 800;
            color: #fbbf24;
            background: rgba(245, 158, 11, 0.15);
            border: 0.2mm solid rgba(245, 158, 11, 0.3);
            padding: 0.5mm 1.5mm;
            border-radius: 0.6mm;
            letter-spacing: 0.1mm;
        }

        /* Konten Utama */
        .card-body {
            height: 42mm;
            padding: 3mm 4mm;
            display: flex;
            gap: 3.5mm;
            box-sizing: border-box;
            align-items: center;
        }

        /* Foto */
        .photo-container {
            width: 19.5mm;
            height: 25.5mm;
            border-radius: 1.5mm;
            overflow: hidden;
            border: 0.3mm solid rgba(251, 191, 36, 0.4);
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            background: rgba(15, 23, 42, 0.5);
            flex-shrink: 0;
            position: relative;
        }

        .photo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(to bottom, #1e293b, #0f172a);
            color: #cbd5e1;
        }

        .photo-placeholder .initials {
            font-size: 5mm;
            font-weight: 800;
            letter-spacing: 0.5mm;
        }

        .photo-placeholder .small-brand {
            font-size: 1.4mm;
            margin-top: 0.8mm;
            opacity: 0.5;
            font-weight: 600;
        }

        .card-status-badge {
            position: absolute;
            bottom: 0.8mm;
            right: 0.8mm;
            background: rgba(16, 185, 129, 0.9);
            color: #ffffff;
            font-size: 1.3mm;
            font-weight: 900;
            padding: 0.3mm 0.8mm;
            border-radius: 0.4mm;
            letter-spacing: 0.2mm;
            display: flex;
            align-items: center;
            gap: 0.3mm;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .card-status-dot {
            width: 0.6mm;
            height: 0.6mm;
            background-color: #ffffff;
            border-radius: 50%;
        }

        /* Detail Data */
        .details-container {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 0;
        }

        .detail-row {
            margin-bottom: 1.2mm;
        }

        .detail-row.last-row {
            margin-bottom: 0;
        }

        .detail-label {
            font-size: 1.5mm;
            font-weight: 500;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.1mm;
            margin: 0 0 0.2mm 0;
        }

        .detail-value {
            font-size: 2.3mm;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .detail-value.name-val {
            font-size: 2.6mm;
            color: #ffffff;
            text-transform: uppercase;
        }

        .detail-value.nim-val {
            font-size: 2.4mm;
            font-family: monospace;
            color: #fde047; /* Yellow text */
            letter-spacing: 0.2mm;
        }

        .details-grid-2x2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5mm 3mm;
            margin-bottom: 1.2mm;
        }

        .details-grid-2x2-bottom {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5mm 3mm;
            padding-top: 1mm;
            border-top: 0.15mm solid rgba(255, 255, 255, 0.08);
        }

        .grid-val {
            font-size: 2mm;
            font-weight: 700;
            color: #e2e8f0;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .grid-val-sub {
            font-size: 1.8mm;
            font-weight: 600;
            color: #cbd5e1;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Bagian Belakang */
        .back-container {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            padding: 3.5mm 4mm;
            box-sizing: border-box;
        }

        .back-header {
            text-align: center;
            border-bottom: 0.2mm solid rgba(255, 255, 255, 0.1);
            padding-bottom: 1.2mm;
        }

        .back-title {
            font-size: 2.1mm;
            font-weight: 800;
            color: #fbbf24;
            letter-spacing: 0.2mm;
            margin: 0;
            text-transform: uppercase;
        }

        .back-rules {
            font-size: 1.65mm;
            color: #cbd5e1;
            line-height: 1.4;
            margin: 2mm 0;
        }

        .rule-item {
            margin-bottom: 0.6mm;
            padding-left: 2.5mm;
            position: relative;
        }

        .rule-item::before {
            content: "•";
            position: absolute;
            left: 0.5mm;
            color: #fbbf24;
        }

        .back-footer {
            border-top: 0.2mm solid rgba(255, 255, 255, 0.1);
            padding-top: 2mm;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        /* QR Code */
        .qr-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex-shrink: 0;
        }

        .qr-wrapper {
            background: #ffffff;
            padding: 0.8mm;
            border-radius: 1mm;
            width: 12.5mm;
            height: 12.5mm;
            box-sizing: border-box;
            box-shadow: 0 2px 4px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-svg {
            width: 100%;
            height: 100%;
            color: #0f172a;
        }

        .qr-text {
            font-size: 1.2mm;
            color: #94a3b8;
            margin-top: 0.8mm;
            font-family: monospace;
            text-transform: uppercase;
            font-weight: 600;
        }

        /* Tanda Tangan */
        .signature-box {
            text-align: right;
            line-height: 1;
            position: relative;
        }

        .sig-date {
            font-size: 1.4mm;
            color: #94a3b8;
            margin: 0 0 0.8mm 0;
        }

        .sig-title {
            font-size: 1.6mm;
            font-weight: 700;
            color: #e2e8f0;
            margin: 0;
        }

        .sig-subtitle {
            font-size: 1.6mm;
            font-weight: 600;
            color: #e2e8f0;
            margin: 0;
        }

        .sig-graphic-area {
            height: 7.5mm;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            position: relative;
            margin: 0.5mm 0;
            padding-right: 2mm;
        }

        .sig-stamp {
            position: absolute;
            right: 4mm;
            bottom: -0.5mm;
            width: 8mm;
            height: 8mm;
            border-radius: 50%;
            border: 0.4mm dashed rgba(6, 182, 212, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            transform: rotate(12deg);
            pointer-events: none;
        }

        .sig-stamp-text {
            font-size: 1mm;
            font-weight: 900;
            color: rgba(6, 182, 212, 0.25);
            text-transform: uppercase;
            letter-spacing: -0.1px;
        }

        .sig-line-svg {
            height: 6mm;
            width: 12mm;
            color: rgba(251, 191, 36, 0.5);
        }

        .sig-name {
            font-size: 1.8mm;
            font-weight: 800;
            color: #fbbf24;
            text-decoration: underline;
            text-decoration-color: rgba(251, 191, 36, 0.3);
            margin: 0 0 0.4mm 0;
        }

        .sig-nim {
            font-size: 1.3mm;
            color: #94a3b8;
            font-family: monospace;
            margin: 0;
        }

        /* Gaya Khusus Mode Print */
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            .print-container {
                margin: 0 !important;
                padding: 10mm !important;
                gap: 15mm;
                height: 100vh;
                justify-content: center;
            }

            .member-card {
                box-shadow: none !important;
                border: 0.25mm solid #fbbf24 !important;
                /* Memastikan tidak ada pergeseran posisi print */
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    {{-- Control Panel (Hanya tampil di layar monitor) --}}
    <div class="no-print max-w-lg mx-auto bg-white p-4 rounded-xl border border-gray-200 shadow-md mb-6 dark:bg-gray-800 dark:border-gray-700 mt-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Siap Mencetak Kartu</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Dialog cetak akan terbuka otomatis. Jika tidak, klik tombol di kanan.</p>
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold px-3 py-1.5 rounded transition shadow-sm flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.844l9.63-9.63m0 0a2.121 2.121 0 013 3l-9.63 9.63m9.63-9.63l-1.125 1.125m-2.25 2.25l-2.249 2.25m-2.25 2.25l-2.25 2.25m-2.25 2.25l-.224.224m10.5-10.5h.008v.008h-.008V8.25zm.008 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V12.75zM12 18.75h.008v.008H12v-.008zM9.75 16.5h.008v.008h-.008v-.008zM7.5 14.25h.008v.008H7.5v-.008zm-2.25-2.25h.008v.008H5.25v-.008zm16.5-4.5h.008v.008h-.008V7.5zM12 12.75h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM12 6h.008v.008H12V6z" />
                    </svg>
                    Cetak
                </button>
                <button onclick="window.close()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold px-3 py-1.5 rounded transition dark:bg-gray-700 dark:hover:bg-gray-650 dark:text-gray-200">
                    Tutup Halaman
                </button>
            </div>
        </div>
    </div>

    {{-- Main Container Cetak --}}
    <div class="print-container">
        
        {{-- KARTU DEPAN --}}
        <div class="member-card">
            <div class="card-grid"></div>
            
            {{-- Header Kop --}}
            <div class="card-header">
                <div class="header-logo-text">
                    <img src="{{ asset('assets/image/logo.png') }}" class="logo-img" alt="Logo BEM">
                    <div class="header-title-container">
                        <h4 class="header-main-title">Badan Eksekutif Mahasiswa</h4>
                        <p class="header-subtitle">STMIK DHARMAPALA RIAU</p>
                    </div>
                </div>
                <span class="card-badge">KTA RESMI</span>
            </div>

            {{-- Body --}}
            <div class="card-body">
                <div class="photo-container">
                    @if ($anggota->foto)
                        <img src="{{ asset('storage/' . $anggota->foto) }}" class="photo-img" alt="Foto">
                    @else
                        <div class="photo-placeholder">
                            <span class="initials">{{ $anggota->inisial }}</span>
                            <span class="small-brand">BEM STMIK-DPR</span>
                        </div>
                    @endif
                    <div class="card-status-badge">
                        <span class="card-status-dot"></span>
                        AKTIF
                    </div>
                </div>

                <div class="details-container">
                    <div class="detail-row">
                        <p class="detail-label">Nama Anggota</p>
                        <h5 class="detail-value name-val">{{ $anggota->nama }}</h5>
                    </div>
                    
                    <div class="detail-row">
                        <p class="detail-label">NIM</p>
                        <p class="detail-value nim-val">{{ $anggota->nim }}</p>
                    </div>

                    <div class="details-grid-2x2">
                        <div>
                            <p class="detail-label">Jabatan</p>
                            <p class="grid-val">{{ $activeKeanggotaan?->jabatan?->nama ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="detail-label">Departemen</p>
                            <p class="grid-val">{{ $activeKeanggotaan?->departemen?->nama ?? 'Pengurus Inti' }}</p>
                        </div>
                    </div>

                    <div class="details-grid-2x2-bottom">
                        <div>
                            <p class="detail-label">Prodi</p>
                            <p class="grid-val-sub">{{ $anggota->prodi ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="detail-label">Angkatan</p>
                            <p class="grid-val-sub">{{ $anggota->angkatan ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KARTU BELAKANG --}}
        <div class="member-card back-card">
            <div class="card-grid"></div>
            
            <div class="back-container">
                <div class="back-header">
                    <h4 class="back-title">KARTU TANDA ANGGOTA</h4>
                </div>

                <div class="back-rules">
                    <div class="rule-item">Kartu ini milik Badan Eksekutif Mahasiswa STMIK Dharmapala Riau.</div>
                    <div class="rule-item">Kartu wajib digunakan dalam setiap agenda resmi organisasi BEM.</div>
                    <div class="rule-item">Penyalahgunaan kartu ini dapat dikenakan sanksi tata tertib organisasi.</div>
                    <div class="rule-item">Jika menemukan kartu ini, harap hubungi Sekretariat BEM STMIK-DPR.</div>
                </div>

                <div class="back-footer">
                    {{-- Info Sekretariat --}}
                    <div style="text-align: left; line-height: 1.2;">
                        <p style="font-size: 1.8mm; font-weight: 800; color: #fbbf24; text-transform: uppercase; margin: 0 0 0.5mm 0; letter-spacing: 0.1mm;">Sekretariat BEM</p>
                        <p style="font-size: 1.4mm; color: #cbd5e1; margin: 0 0 0.3mm 0;">STMIK Dharmapala Riau</p>
                        <p style="font-size: 1.4mm; color: #94a3b8; margin: 0;">Pekanbaru, Riau, Indonesia</p>
                    </div>

                    {{-- Tanda Tangan Presiden Mahasiswa --}}
                    <div class="signature-box">
                        <p class="sig-date">Pekanbaru, {{ \Carbon\Carbon::now()->format('d M Y') }}</p>
                        <p class="sig-title">Pengurus BEM,</p>
                        <p class="sig-subtitle">Presiden Mahasiswa</p>
                        
                        <div class="sig-graphic-area">
                            {{-- Stamp mockup --}}
                            <div class="sig-stamp">
                                <span class="sig-stamp-text">BEM STMIK-DPR</span>
                            </div>
                            {{-- Signature mockup path --}}
                            <svg class="sig-line-svg" viewBox="0 0 100 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 25c15-15 35 15 45-5s20-25 35-5M25 15c5 5 15-15 10 20M55 35c5-10 15 10 10-10" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>

                        <p class="sig-name">{{ $presiden ? $presiden->nama : 'TENGKU ZAINUL' }}</p>
                        <p class="sig-nim">NIM. {{ $presiden ? $presiden->nim : '210402071' }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Script Auto Print --}}
    <script>
        window.addEventListener('load', () => {
            // Beri jeda 800ms agar font/asset terload sempurna
            setTimeout(() => {
                window.print();
            }, 800);
        });
    </script>
</body>
</html>
