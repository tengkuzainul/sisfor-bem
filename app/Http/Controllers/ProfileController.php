<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Keanggotaan;
use App\Models\Kepengurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Tampilkan profil pengguna saat ini.
     */
    public function show()
    {
        $user = Auth::user();
        $activeKepengurusan = Kepengurusan::getActive();
        $anggota = null;
        $activeKeanggotaan = null;
        $presiden = null;

        if ($user->anggota_id) {
            $anggota = Anggota::with(['keanggotaan' => function ($q) use ($activeKepengurusan) {
                $q->where('status', 'aktif')
                    ->when($activeKepengurusan, fn ($q2) => $q2->where('kepengurusan_id', $activeKepengurusan->id))
                    ->with(['departemen', 'jabatan', 'kepengurusan']);
            }])->find($user->anggota_id);

            if ($anggota) {
                $activeKeanggotaan = $anggota->keanggotaan->first();
            }
        }

        // Cari presiden mahasiswa secara dinamis untuk penandatangan kartu
        if ($activeKepengurusan) {
            $presidenKeanggotaan = Keanggotaan::query()->where('kepengurusan_id', $activeKepengurusan->id)
                ->whereHas('jabatan', function ($query) {
                    $query->where('nama', 'like', '%presiden%')
                        ->where('nama', 'not like', '%wakil%');
                })
                ->with('anggota')
                ->first();
            $presiden = $presidenKeanggotaan ? $presidenKeanggotaan->anggota : null;
        }

        return view('profile.show', compact('user', 'anggota', 'activeKeanggotaan', 'activeKepengurusan', 'presiden'));
    }

    /**
     * Perbarui password pengguna.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak cocok dengan data kami.']);
        }

        $user->update([
            'password' => $request->password,
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Tampilkan layout cetak kartu anggota BEM.
     */
    public function print()
    {
        $user = Auth::user();
        if (! $user->anggota_id) {
            return redirect()->route('profile.show')->with('error', 'Akun Anda tidak terhubung dengan profil Anggota BEM.');
        }

        $activeKepengurusan = Kepengurusan::getActive();

        $anggota = Anggota::with(['keanggotaan' => function ($q) use ($activeKepengurusan) {
            $q->where('status', 'aktif')
                ->when($activeKepengurusan, fn ($q2) => $q2->where('kepengurusan_id', $activeKepengurusan->id))
                ->with(['departemen', 'jabatan', 'kepengurusan']);
        }])->find($user->anggota_id);

        if (! $anggota) {
            return redirect()->route('profile.show')->with('error', 'Profil Anggota tidak ditemukan.');
        }

        $activeKeanggotaan = $anggota->keanggotaan->first();

        // Cari presiden mahasiswa secara dinamis
        $presiden = null;
        if ($activeKepengurusan) {
            $presidenKeanggotaan = Keanggotaan::query()->where('kepengurusan_id', $activeKepengurusan->id)
                ->whereHas('jabatan', function ($query) {
                    $query->where('nama', 'like', '%presiden%')
                        ->where('nama', 'not like', '%wakil%');
                })
                ->with('anggota')
                ->first();
            $presiden = $presidenKeanggotaan ? $presidenKeanggotaan->anggota : null;
        }

        return view('profile.print', compact('anggota', 'activeKeanggotaan', 'activeKepengurusan', 'presiden'));
    }

    /**
     * Verifikasi kartu anggota BEM secara publik (dari QR Code).
     */
    public function verify($nim)
    {
        $anggota = Anggota::query()->where('nim', $nim)->first();

        if (! $anggota) {
            return view('profile.verify', [
                'success' => false,
                'message' => 'Anggota dengan NIM tersebut tidak terdaftar di sistem BEM STMIK Dharmapala Riau.',
            ]);
        }

        // Ambil keanggotaan aktif
        $activeKeanggotaan = Keanggotaan::query()
            ->where('anggota_id', $anggota->id)
            ->where('status', 'aktif')
            ->with(['departemen', 'jabatan', 'kepengurusan'])
            ->first();

        return view('profile.verify', [
            'success' => true,
            'anggota' => $anggota,
            'activeKeanggotaan' => $activeKeanggotaan,
        ]);
    }
}
