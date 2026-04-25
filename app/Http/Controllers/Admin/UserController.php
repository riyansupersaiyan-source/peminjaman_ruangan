<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // <-- Import User Model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // <-- Import Hash facade
use Illuminate\Validation\Rule; // <-- Import Rule facade
use Illuminate\Validation\Rules\Password; // <-- Import Password rules

class UserController extends Controller
{
    /**
     * Tampilkan daftar user (Dosen & Mahasiswa).
     */
    public function index()
    {
        // Ambil user HANYA yang role-nya 'dosen' atau 'mahasiswa'
        $users = User::whereIn('role', ['dosen', 'mahasiswa'])
                     ->latest() // Urutkan dari yang terbaru
                     ->paginate(10);
                     
        return view('admin.users.index', compact('users'));
    }

    /**
     * Tampilkan formulir untuk membuat user baru.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Simpan user baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => ['required', Rule::in(['dosen', 'mahasiswa'])],
        ]);

        // Enkripsi password sebelum disimpan
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'User baru berhasil ditambahkan.');
    }

    /**
     * Tampilkan formulir untuk mengedit user.
     */
    public function edit(User $user)
    {
        // Pastikan kita tidak mengedit role selain dosen/mahasiswa via URL
        if ($user->isAdmin()) {
            abort(403);
        }
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update data user di database.
     */
    public function update(Request $request, User $user)
    {
        // Validasi
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Email harus unik, tapi abaikan user_id saat ini
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['dosen', 'mahasiswa'])],
            // Password bersifat opsional (kosongkan jika tidak ingin diubah)
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        // Update data dasar
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        // Cek jika Admin mengisi password baru
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Simpan perubahan
        $user->save();

        return redirect()->route('admin.users.index')
                         ->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Hapus user dari database.
     */
    public function destroy(User $user)
    {
        try {
            // Pastikan admin tidak bisa menghapus role admin lain (meski sudah difilter)
            if ($user->isAdmin()) {
                 return redirect()->route('admin.users.index')
                                 ->with('error', 'Admin tidak dapat dihapus.');
            }

            $user->delete();
            return redirect()->route('admin.users.index')
                             ->with('success', 'User berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                             ->with('error', 'Gagal menghapus user.');
        }
    }
}