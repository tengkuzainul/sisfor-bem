<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KategoriProkerController;
use App\Http\Controllers\KepengurusanController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PendaftarController;
use App\Http\Controllers\ProgramKerjaController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\RekrutmenController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public / Frontend
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/proker/{programKerja}', [HomeController::class, 'prokerDetail'])->name('home.proker.detail');
Route::get('/api/calendar-events', [ProgramKerjaController::class, 'calendarEvents'])->name('api.calendar-events');

/*
|--------------------------------------------------------------------------
| Pendaftaran / Rekrutmen (Public)
|--------------------------------------------------------------------------
*/
Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
Route::get('/pendaftaran/{slug}', [PendaftaranController::class, 'form'])->name('pendaftaran.form');
Route::post('/pendaftaran/{slug}', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
Route::get('/pendaftaran/{slug}/success', [PendaftaranController::class, 'success'])->name('pendaftaran.success');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Dashboard (requires auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |----------------------------------------------------------------------
    | Manajemen Organisasi (admin, pembina)
    |----------------------------------------------------------------------
    */
    Route::middleware('role:administrator,pembina')->group(function () {
        Route::resource('kepengurusan', KepengurusanController::class);
        Route::patch('kepengurusan/{kepengurusan}/activate', [KepengurusanController::class, 'activate'])->name('kepengurusan.activate');
        Route::patch('kepengurusan/{kepengurusan}/deactivate', [KepengurusanController::class, 'deactivate'])->name('kepengurusan.deactivate');
        Route::resource('departemen', DepartemenController::class)
            ->parameters(['departemen' => 'departemen'])
            ->except(['show']);
        Route::resource('jabatan', JabatanController::class)->except(['show']);
    });

    // Anggota — semua role bisa melihat, tapi CRUD hanya admin/pembina
    Route::resource('anggota', AnggotaController::class)
        ->parameters(['anggota' => 'anggota'])
        ->except(['show']);

    /*
    |----------------------------------------------------------------------
    | Program Kerja
    |----------------------------------------------------------------------
    */
    Route::resource('kategori-proker', KategoriProkerController::class)->except(['show'])
        ->middleware('role:administrator,pembina');
    Route::resource('program-kerja', ProgramKerjaController::class);

    /*
    |----------------------------------------------------------------------
    | Manajemen Pengguna (admin only)
    |----------------------------------------------------------------------
    */
    Route::resource('users', UserController::class)->except(['show'])->middleware('role:administrator');

    /*
    |----------------------------------------------------------------------
    | Proposal Kegiatan
    |----------------------------------------------------------------------
    */
    Route::resource('proposal', ProposalController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('proposal/{proposal}/revise', [ProposalController::class, 'revise'])->name('proposal.revise');
    Route::post('proposal/{proposal}/review', [ProposalController::class, 'review'])->name('proposal.review');

    /*
    |----------------------------------------------------------------------
    | E-Rekrutmen
    |----------------------------------------------------------------------
    */
    Route::middleware('role:administrator,pembina')->group(function () {
        Route::resource('rekrutmen', RekrutmenController::class)->parameters(['rekrutmen' => 'rekrutmen']);
        Route::patch('rekrutmen/{rekrutmen}/update-status', [RekrutmenController::class, 'updateStatus'])->name('rekrutmen.update-status');
    });

    Route::get('pendaftar', [PendaftarController::class, 'index'])->name('pendaftar.index');
    Route::get('pendaftar/{pendaftar}', [PendaftarController::class, 'show'])->name('pendaftar.show');
    Route::post('pendaftar/{pendaftar}/review', [PendaftarController::class, 'review'])->name('pendaftar.review');
    Route::patch('pendaftar/{pendaftar}/update-status', [PendaftarController::class, 'updateStatus'])->name('pendaftar.update-status');
}); // end auth middleware
