<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model; // <-- Gunakan Model MongoDB
use App\Models\User;
use App\Models\Room;
use App\Models\ActivityType;

class Booking extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'bookings';

    protected $fillable = [
        'user_id',
        'room_id',
        'activity_type_id',
        'start_time',
        'end_time',
        'purpose',
        'status',
        'supervisor_name',
        'rejection_reason',
        'approved_by',
    ];

    /**
     * Konversi otomatis ke objek Carbon (Tanggal/Waktu) menggunakan $casts.
     * Ini adalah cara yang direkomendasikan di Laravel modern, berfungsi sama seperti $dates.
     */
    protected $casts = [
        'start_time' => 'datetime', // <-- Diubah dari $dates
        'end_time' => 'datetime',   // <-- Diubah dari $dates
    ];

    // Menghapus protected $dates jika menggunakan $casts
    // protected $dates = [
    //     'start_time',
    //     'end_time',
    // ];


    /**
     * Relasi "belongsTo" ke User (Pemohon)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi "belongsTo" ke Ruangan
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Relasi "belongsTo" ke Jenis Kegiatan
     */
    public function activityType()
    {
        return $this->belongsTo(ActivityType::class);
    }
}