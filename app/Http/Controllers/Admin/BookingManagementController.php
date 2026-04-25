<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking; // <-- Import model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Import Auth

class BookingManagementController extends Controller
{
    /**
     * Tampilkan daftar SEMUA peminjaman (pending, approved, rejected).
     */
    public function index()
    {
        // Ambil semua data peminjaman, diurutkan dari yang terbaru
        // Gunakan 'with' (Eager Loading) untuk mengambil data relasi (user & room)
        // Ini mencegah N+1 query problem di view
        $bookings = Booking::with(['user', 'room'])
                           ->latest() // Urutkan dari yang terbaru dibuat
                           ->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Setujui peminjaman yang masih pending.
     */
    public function approve(Booking $booking)
    {
        // Hanya setujui jika statusnya masih 'pending'
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Peminjaman ini sudah diproses.');
        }
        
        // --- 🚀 LOGIKA PENTING: Cek Konflik Jadwal ---
        // Sebelum disetujui, cek sekali lagi apakah ada jadwal BENTROK
        // yang sudah 'approved' di ruangan dan waktu yang sama.
        
        $konflik = Booking::where('room_id', $booking->room_id)
            ->where('status', 'approved') // Hanya cek yang sudah disetujui
            ->where(function ($query) use ($booking) {
                $query->where('start_time', '<', $booking->end_time)
                      ->where('end_time', '>', $booking->start_time);
            })
            ->exists(); // Cek apakah ada (true/false)

        if ($konflik) {
            return back()->with('error', 'GAGAL: Peminjaman tidak dapat disetujui karena bentrok dengan jadwal lain yang sudah disetujui.');
        }

        // --- Jika tidak ada konflik, setujui ---
        $booking->status = 'approved';
        $booking->approved_by = Auth::id(); // Catat siapa Admin yang menyetujui
        $booking->rejection_reason = null;
        $booking->save();

        // (Opsional: Kirim Notifikasi Email ke user)

        return back()->with('success', 'Peminjaman berhasil disetujui.');
    }

    /**
     * Tolak peminjaman yang masih pending.
     */
    public function reject(Request $request, Booking $booking)
    {
        // Hanya tolak jika statusnya masih 'pending'
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Peminjaman ini sudah diproses.');
        }

        // Admin wajib memberikan alasan penolakan
        $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500',
        ]);

        $booking->status = 'rejected';
        $booking->rejection_reason = $request->rejection_reason;
        $booking->save();

        // (Opsional: Kirim Notifikasi Email ke user)

        return back()->with('success', 'Peminjaman telah ditolak.');
    }
}