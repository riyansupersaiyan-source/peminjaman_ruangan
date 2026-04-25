<x-app-layout> {{-- Menggunakan layout standar (bukan admin) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Selamat Datang, {{ Auth::user()->name }}!</h3>
                    <p class="mt-2 text-gray-600">Anda login sebagai {{ ucfirst(Auth::user()->role) }}.</Post>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('bookings.create') }}" class="block p-6 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 transition">
                    <h3 class="font-semibold text-xl">Ajukan Peminjaman Baru</h3>
                    <p class="mt-1 opacity-90">Klik di sini untuk mengisi formulir peminjaman ruangan.</p>
                </a>
                <a href="{{ route('bookings.index') }}" class="block p-6 bg-white rounded-lg shadow-sm border border-gray-200 hover:bg-gray-50 transition">
                    <h3 class="font-semibold text-xl text-gray-900">Lihat Riwayat Saya</h3>
                    <p class="mt-1 text-gray-600">Lihat semua riwayat peminjaman Anda (Pending, Approved, Rejected).</p>
                </a>
            </div>

            <div class="mt-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Peminjaman Anda yang Akan Datang (Disetujui)</h3>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-0">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Mulai</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Selesai</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($upcomingBookings as $booking)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $booking->room->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->start_time->format('d M Y, H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->end_time->format('d M Y, H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Anda tidak memiliki peminjaman yang akan datang.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>