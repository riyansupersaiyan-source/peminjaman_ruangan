<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                 <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemohon</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tujuan</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($bookings as $booking)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($booking->status == 'pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        @elseif($booking->status == 'approved')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                        @elseif($booking->status == 'rejected')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($booking->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $booking->user->name }}
                                        <span class="text-xs text-gray-500">({{ $booking->user->role }})</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->room->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{-- Format tanggal dan waktu --}}
                                        {{ $booking->start_time->format('d M Y, H:i') }} - {{ $booking->end_time->format('H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ $booking->purpose }}</td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if ($booking->status == 'pending')
                                            <form action="{{ route('admin.bookings.approve', $booking) }}" method="POST" class="inline-block" onsubmit="return confirm('Anda yakin ingin MENYETUJUI peminjaman ini?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-600 hover:text-green-900">Setujui</button>
                                            </form>

                                            <form action="{{ route('admin.bookings.reject', $booking) }}" method="POST" class="inline-block" id="form-reject-{{ $booking->_id }}" onsubmit="return confirmReject(event, '{{ $booking->_id }}');">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="rejection_reason" id="reason-{{ $booking->_id }}" value="">
                                                <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Tolak</button>
                                            </form>
                                        @elseif ($booking->status == 'rejected')
                                            <span class="text-xs text-red-700">Ditolak: {{ $booking->rejection_reason }}</span>
                                        @else
                                            <span class="text-xs text-gray-500">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Belum ada data peminjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="mt-4">
                        {{ $bookings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Sederhana untuk Prompt Alasan Penolakan --}}
    <script>
        function confirmReject(event, bookingId) {
            event.preventDefault(); // Hentikan form submit
            
            const reason = prompt("Harap masukkan alasan penolakan:");

            if (reason && reason.trim().length >= 10) {
                // Jika alasan diisi dan valid, masukkan ke input hidden dan submit form
                document.getElementById('reason-' + bookingId).value = reason;
                document.getElementById('form-reject-' + bookingId).submit();
            } else if (reason !== null) {
                // Jika user klik OK tapi alasan kurang dari 10 karakter
                alert("Alasan penolakan wajib diisi (minimal 10 karakter).");
            }
            // Jika user klik Cancel (prompt mengembalikan null), tidak terjadi apa-apa
            return false;
        }
    </script>
</x-admin-layout>