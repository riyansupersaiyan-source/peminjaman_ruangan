<x-admin-layout> {{-- <-- DIUBAH KEMBALI (lebih sederhana) --}}
    {{-- Kita gunakan slot 'header' untuk judul halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Selamat datang, Admin!") }}
                </div>
            </div>

            {{-- 🚀 AREA STATISTIK 🚀 --}}
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <div class="bg-yellow-100 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-yellow-800">Permintaan Pending</h3>
                        <p class="mt-2 text-3xl font-bold text-yellow-900">
                            {{ $pendingBookings }}
                        </p>
                        <a href="{{ route('admin.bookings.index') }}" class="mt-3 inline-block text-sm text-yellow-700 hover:text-yellow-900">
                            Lihat Detail &rarr;
                        </a>
                    </div>
                </div>

                <div class="bg-green-100 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-green-800">Peminjaman Hari Ini</h3>
                        <p class="mt-2 text-3xl font-bold text-green-900">
                            {{ $todayBookings }}
                        </p>
                         <a href="{{ route('admin.bookings.index') }}" class="mt-3 inline-block text-sm text-green-700 hover:text-green-900">
                            Lihat Kalender &rarr;
                        </a>
                    </div>
                </div>
                
                <div class="bg-blue-100 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-blue-800">Total Ruangan Aktif</h3>
                        <p class="mt-2 text-3xl font-bold text-blue-900">
                            {{ $totalRooms }}
                        </p>
                         <a href="{{ route('admin.rooms.index') }}" class="mt-3 inline-block text-sm text-blue-700 hover:text-blue-900">
                            Kelola Ruangan &rarr;
                        </a>
                    </div>
                </div>

                <div class="bg-indigo-100 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-indigo-800">Total Dosen & Mhs</h3>
                        <p class="mt-2 text-3xl font-bold text-indigo-900">
                            {{ $totalUsers }}
                        </p>
                         <a href="{{ route('admin.users.index') }}" class="mt-3 inline-block text-sm text-indigo-700 hover:text-indigo-900">
                            Kelola User &rarr;
                        </a>
                    </div>
                </div>

            </div>
            {{-- AKHIR AREA STATISTIK --}}

        </div>
    </div>
</x-admin-layout> {{-- <-- DIUBAH KEMBALI (lebih sederhana) --}}