<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\ActivityType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Tampilkan riwayat peminjaman milik user yang sedang login.
     */
    public function index()
    {
        /** @var \App\Models\User $user */ // <-- Petunjuk untuk editor
        $user = Auth::user();
        
        $myBookings = Booking::with(['room', 'activityType'])
                            ->where('user_id', $user->id) // <-- Diganti
                            ->latest()
                            ->paginate(10);
                            
        return view('bookings.index', compact('myBookings'));
    }

    /**
     * Tampilkan formulir untuk membuat peminjaman baru.
     */
    public function create()
    {
        $rooms = Room::where('status', 'available')->orderBy('name')->get();
        $activityTypes = ActivityType::orderBy('name')->get();

        return view('bookings.create', compact('rooms', 'activityTypes'));
    }

    /**
     * Simpan peminjaman baru ke database.
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */ // <-- Petunjuk untuk editor
        $user = Auth::user();

        // 1. Validasi input
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,_id',
            'activity_type_id' => 'required|exists:activity_types,_id',
            'purpose' => 'required|string|min:10|max:500',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            // 'supervisor_name' wajib diisi jika user adalah mahasiswa
            'supervisor_name' => $user->isMahasiswa() ? 'required|string' : 'nullable|string', // <-- Diganti
        ], [
            'start_time.after' => 'Waktu mulai harus setelah waktu saat ini.'
        ]);

        // --- Pengecekan Konflik Jadwal ---
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = Carbon::parse($validated['end_time']);

        $konflik = Booking::where('room_id', $validated['room_id'])
            ->whereIn('status', ['approved', 'pending']) 
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
            })
            ->exists();

        if ($konflik) {
            return back()->withInput()->with('error', 'Jadwal di ruangan tersebut sudah terisi (Pending/Approved). Silakan pilih waktu atau ruangan lain.');
        }

        // 3. Jika tidak ada konflik, simpan data
        Booking::create([
            'user_id' => $user->id, // <-- Diganti
            'room_id' => $validated['room_id'],
            'activity_type_id' => $validated['activity_type_id'],
            'purpose' => $validated['purpose'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'supervisor_name' => $validated['supervisor_name'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.index')
                         ->with('success', 'Permintaan peminjaman berhasil diajukan dan sedang menunggu persetujuan Admin.');
    }

    /**
     * Tampilkan detail satu peminjaman.
     */
    public function show(Booking $booking)
    {
        //
    }


    /**
     * Batalkan peminjaman (hanya jika status masih 'pending').
     */
    public function cancel(Booking $booking)
    {
        /** @var \App\Models\User $user */ // <-- Petunjuk untuk editor
        $user = Auth::user();

        // Pastikan user hanya bisa membatalkan miliknya sendiri
        if ($booking->user_id !== $user->id) { // <-- Diganti
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Peminjaman yang sudah diproses tidak dapat dibatalkan.');
        }

        $booking->status = 'cancelled';
        $booking->save();

        return back()->with('success', 'Peminjaman berhasil dibatalkan.');
    }
    
    public function edit(Booking $booking)
    {
        abort(404);
    }

    public function update(Request $request, Booking $booking)
    {
        abort(404);
    }
    
    public function destroy(Booking $booking)
    {
        abort(404);
    }
}