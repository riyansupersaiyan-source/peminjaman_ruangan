<x-app-layout> {{-- Menggunakan layout standar (bukan admin) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Formulir Pengajuan Peminjaman Ruangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg">
                            <p class="font-medium">Oops! Ada kesalahan:</p>
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    @if (session('error'))
                         <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg shadow-sm">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('bookings.store') }}">
                        @csrf

                        <div class="mt-4">
                            <x-input-label for="room_id" :value="__('Pilih Ruangan')" />
                            <select name="room_id" id="room_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="" disabled selected>Pilih salah satu ruangan</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->_id }}" {{ old('room_id') == $room->_id ? 'selected' : '' }}>
                                        {{ $room->name }} (Kapasitas: {{ $room->capacity }} orang)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="activity_type_id" :value="__('Jenis Kegiatan')" />
                            <select name="activity_type_id" id="activity_type_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="" disabled selected>Pilih jenis kegiatan</option>
                                @foreach ($activityTypes as $activity)
                                    <option value="{{ $activity->_id }}" {{ old('activity_type_id') == $activity->_id ? 'selected' : '' }}>
                                        {{ $activity->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mt-4">
                            <x-input-label for="start_time" :value="__('Waktu Mulai')" />
                            <x-text-input id="start_time" class="block mt-1 w-full" type="datetime-local" name="start_time" :value="old('start_time')" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="end_time" :value="__('Waktu Selesai')" />
                            <x-text-input id="end_time" class="block mt-1 w-full" type="datetime-local" name="end_time" :value="old('end_time')" required />
                        </div>
                        
                        <div class="mt-4">
                            <x-input-label for="purpose" :value="__('Tujuan Peminjaman / Nama Kegiatan')" />
                            <textarea id="purpose" name="purpose" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('purpose') }}</textarea>
                        </div>
                        
                        @if(Auth::user()->isMahasiswa())
                            <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-md">
                                <x-input-label for="supervisor_name" :value="__('Nama Dosen Pembimbing / Penanggung Jawab Organisasi')" />
                                <p class="text-sm text-gray-600 mb-2">Wajib diisi oleh Mahasiswa sebagai penanggung jawab.</p>
                                <x-text-input id="supervisor_name" class="block mt-1 w-full" type="text" name="supervisor_name" :value="old('supervisor_name')" required />
                            </div>
                        @endif


                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Ajukan Peminjaman') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>