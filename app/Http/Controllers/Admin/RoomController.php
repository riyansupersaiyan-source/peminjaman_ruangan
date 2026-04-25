<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room; // <-- Import Model Room
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Tampilkan daftar semua ruangan.
     */
    public function index()
    {
        // Ambil semua data ruangan, paginasi 10 per halaman
        $rooms = Room::paginate(10);
        
        // Tampilkan view dan kirim data $rooms
        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * Tampilkan formulir untuk membuat ruangan baru.
     */
    public function create()
    {
        return view('admin.rooms.create');
    }

    /**
     * Simpan ruangan baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:available,maintenance',
            'facilities' => 'nullable|string', // Terima sebagai string (dipisah koma)
        ]);

        // 2. Konversi 'facilities' dari string (cth: "AC, Proyektor") menjadi array
        if (!empty($validated['facilities'])) {
            $validated['facilities'] = array_map('trim', explode(',', $validated['facilities']));
        } else {
            $validated['facilities'] = [];
        }

        // 3. Buat data baru
        Room::create($validated);

        // 4. Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.rooms.index')
                         ->with('success', 'Ruangan baru berhasil ditambahkan.');
    }

    /**
     * Tampilkan formulir untuk mengedit ruangan.
     */
    public function edit(Room $room) // Gunakan Route-Model Binding
    {
        // Tampilkan view edit dan kirim data $room yang ingin diedit
        return view('admin.rooms.edit', compact('room'));
    }

    /**
     * Update data ruangan di database.
     */
    public function update(Request $request, Room $room)
    {
        // 1. Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:available,maintenance',
            'facilities' => 'nullable|string',
        ]);

        // 2. Konversi 'facilities' dari string menjadi array
        if (!empty($validated['facilities'])) {
            $validated['facilities'] = array_map('trim', explode(',', $validated['facilities']));
        } else {
            $validated['facilities'] = [];
        }

        // 3. Update data
        $room->update($validated);

        // 4. Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.rooms.index')
                         ->with('success', 'Data ruangan berhasil diperbarui.');
    }

    /**
     * Hapus ruangan dari database.
     */
    public function destroy(Room $room)
    {
        // Hati-hati: Sebaiknya tambahkan pengecekan
        // apakah ruangan sedang di-booking sebelum menghapus.
        // Untuk saat ini, kita langsung hapus.
        
        try {
            $room->delete();
            return redirect()->route('admin.rooms.index')
                             ->with('success', 'Ruangan berhasil dihapus.');
        } catch (\Exception $e) {
            // Tangani jika ada error (misal: foreign key constraint jika pakai SQL)
            return redirect()->route('admin.rooms.index')
                             ->with('error', 'Gagal menghapus ruangan.');
        }
    }
}