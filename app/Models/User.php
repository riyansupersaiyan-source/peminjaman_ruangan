<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Auth\User as Authenticatable; // <-- Pastikan ini benar
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Booking; // <-- Pastikan ini ada

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Tentukan koneksi database yang digunakan oleh model ini.
     */
    protected $connection = 'mongodb';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // <-- Pastikan 'role' ada di sini
    ];

    /**
     * The attributes that are hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // --- 🚀 TAMBAHAN: Relasi & Helper Role ---

    /**
     * Relasi ke peminjaman yang dibuat oleh user (Dosen/Mahasiswa).
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // ==========================================================
    // === ❗️ BAGIAN INI YANG MENYEBABKAN ERROR JIKA HILANG ===
    // ==========================================================

    /**
     * Cek apakah user adalah Admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah Dosen.
     */
    public function isDosen()
    {
        return $this->role === 'dosen';
    }

    /**
     * Cek apakah user adalah Mahasiswa.
     */
    public function isMahasiswa()
    {
        return $this->role === 'mahasiswa';
    }
}