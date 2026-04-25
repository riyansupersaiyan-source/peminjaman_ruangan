<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard yang sesuai berdasarkan peran pengguna.
     */
    public function index()
    {
        // --- 🚀 PERBAIKAN UNTUK INTELEPHENSE ---
        /** @var \App\Models\User $user */ // <-- 1. Beri tahu editor tipe variabel $user
        $user = Auth::user(); // <-- 2. Simpan user ke variabel
        // ----------------------------------------

        // Cek jika pengguna adalah admin
        if ($user->isAdmin()) { // <-- 3. Gunakan $user->isAdmin()
            
            // Arahkan ke rute dashboard admin
            return redirect()->route('admin.dashboard');
        
        } else {
            // --- LOGIKA UNTUK DOSEN/MAHASISWA ---
            $upcomingBookings = Booking::with('room')
                ->where('user_id', $user->id) // <-- 4. Gunakan $user->id
                ->where('status', 'approved')
                ->where('start_time', '>=', Carbon::now()) 
                ->orderBy('start_time', 'asc')
                ->take(5)
                ->get();
                
            return view('dashboard', compact('upcomingBookings'));
        }
    }
}