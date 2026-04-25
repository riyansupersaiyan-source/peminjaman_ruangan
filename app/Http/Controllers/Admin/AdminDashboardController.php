<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Pastikan use statement di bawah ini lengkap
use App\Models\Room;
use App\Models\User;
use App\Models\Booking;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // === BLOK TRY-CATCH UNTUK MENCEGAH CRASH LAYAR PUTIH ===
        try {
            // 1. Hitung total ruangan
            $totalRooms = Room::count();

            // 2. Hitung total pengguna (Dosen & Mahasiswa)
            $totalUsers = User::whereIn('role', ['dosen', 'mahasiswa'])->count();

            // 3. Hitung total peminjaman yang masih 'pending'
            $pendingBookings = Booking::where('status', 'pending')->count();

            // 4. Hitung total peminjaman yang 'approved' untuk HARI INI
            // Menggunakan perbandingan waktu eksplisit untuk keandalan MongoDB
            $today = Carbon::today();
            $tomorrow = Carbon::tomorrow();

            $todayBookings = Booking::where('status', 'approved')
                                    ->where('start_time', '>=', $today)
                                    ->where('start_time', '<', $tomorrow) 
                                    ->count();

            // Jika semua query berhasil, baru tampilkan view
            return view('admin.dashboard', compact(
                'totalRooms',
                'totalUsers',
                'pendingBookings',
                'todayBookings'
            ));

        } catch (\Exception $e) {
            // AKTIFKAN BARIS DEBUG INI UNTUK MELIHAT PESAN ERROR
            dd($e->getMessage()); 
        }
    }
}