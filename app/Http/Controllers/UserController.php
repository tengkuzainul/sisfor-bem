<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::with('anggota');

            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('role', 'like', "%{$search}%");
                });
            }

            $sortBy  = $request->input('sort_by', 'name');
            $sortDir = $request->input('sort_dir', 'asc');
            $query->orderBy($sortBy, $sortDir);

            $paginated = $query->paginate($request->input('per_page', 10));

            $paginated->getCollection()->transform(function ($user) {
                $user->append(['role_label', 'role_color']);
                return $user;
            });

            return response()->json($paginated);
        }

        return view('users.index');
    }

    public function create()
    {
        $anggotaList = Anggota::whereDoesntHave('user')
            ->orderBy('nama')
            ->get(['id', 'nama', 'nim']);
        $roles = User::ROLES;

        return view('users.create', compact('anggotaList', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|string|min:6|confirmed',
            'role'       => 'required|in:' . implode(',', array_keys(User::ROLES)),
            'anggota_id' => 'nullable|exists:anggota,id',
        ]);

        $user = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => $request->password,
            'role'       => $request->role,
            'anggota_id' => $request->anggota_id ?: null,
        ]);

        // Link back from anggota
        if ($user->anggota_id) {
            Anggota::where('id', $user->anggota_id)->update(['user_id' => $user->id]);
        }

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $anggotaList = Anggota::where(function ($q) use ($user) {
            $q->whereDoesntHave('user')
              ->orWhere('id', $user->anggota_id);
        })->orderBy('nama')->get(['id', 'nama', 'nim']);

        $roles = User::ROLES;

        return view('users.edit', compact('user', 'anggotaList', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password'   => 'nullable|string|min:6|confirmed',
            'role'       => 'required|in:' . implode(',', array_keys(User::ROLES)),
            'anggota_id' => 'nullable|exists:anggota,id',
        ]);

        // Unlink previous anggota
        if ($user->anggota_id && $user->anggota_id != $request->anggota_id) {
            Anggota::where('id', $user->anggota_id)->update(['user_id' => null]);
        }

        $user->update([
            'name'       => $request->name,
            'email'      => $request->email,
            'role'       => $request->role,
            'anggota_id' => $request->anggota_id ?: null,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => $request->password]);
        }

        // Link new anggota
        if ($user->anggota_id) {
            Anggota::where('id', $user->anggota_id)->update(['user_id' => $user->id]);
        }

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Unlink anggota
        if ($user->anggota_id) {
            Anggota::where('id', $user->anggota_id)->update(['user_id' => null]);
        }

        $user->delete();

        $msg = 'Pengguna berhasil dihapus.';
        return request()->ajax()
            ? response()->json(['message' => $msg])
            : redirect()->route('users.index')->with('success', $msg);
    }
}
