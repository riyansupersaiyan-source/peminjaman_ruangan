<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityType; // <-- Import Model
use Illuminate\Http\Request;

class ActivityTypeController extends Controller
{
    /**
     * Tampilkan daftar semua jenis kegiatan.
     */
    public function index()
    {
        $activityTypes = ActivityType::paginate(10);
        return view('admin.activity-types.index', compact('activityTypes'));
    }

    /**
     * Tampilkan formulir untuk membuat data baru.
     */
    public function create()
    {
        return view('admin.activity-types.create');
    }

    /**
     * Simpan data baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        // Buat data baru
        ActivityType::create($validated);

        return redirect()->route('admin.activity-types.index')
                         ->with('success', 'Jenis Kegiatan baru berhasil ditambahkan.');
    }

    /**
     * Tampilkan formulir untuk mengedit data.
     */
    public function edit(ActivityType $activityType) // Gunakan Route-Model Binding
    {
        return view('admin.activity-types.edit', compact('activityType'));
    }

    /**
     * Update data di database.
     */
    public function update(Request $request, ActivityType $activityType)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        // Update data
        $activityType->update($validated);

        return redirect()->route('admin.activity-types.index')
                         ->with('success', 'Jenis Kegiatan berhasil diperbarui.');
    }

    /**
     * Hapus data dari database.
     */
    public function destroy(ActivityType $activityType)
    {
        try {
            // (Nanti bisa ditambahkan cek jika jenis kegiatan ini sudah dipakai)
            $activityType->delete();
            return redirect()->route('admin.activity-types.index')
                             ->with('success', 'Jenis Kegiatan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.activity-types.index')
                             ->with('error', 'Gagal menghapus data.');
        }
    }
}