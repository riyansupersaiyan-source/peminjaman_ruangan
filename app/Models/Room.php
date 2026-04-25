<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model; // <-- Gunakan Model MongoDB
use App\Models\Booking;

class Room extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'rooms'; // Menentukan nama "tabel" (collection)

    protected $fillable = [
        'name',
        'capacity',
        'facilities', // Akan disimpan sebagai array
        'status', // 'available', 'maintenance', dll.
    ];

    /**
     * Tipe data yang di-cast otomatis.
     */
    protected $casts = [
        'capacity' => 'integer',
        'facilities' => 'array',
    ];

    /**
     * Relasi ke Peminjaman
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}