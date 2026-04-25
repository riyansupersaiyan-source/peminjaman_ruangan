<x-admin-layout> {{-- <-- HARUS JADI TAG PEMBUKA --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Ruangan: ') . $room->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.rooms.update', $room) }}">
                        @csrf
                        @method('PATCH') {{-- PENTING: Gunakan method PATCH untuk update --}}

                        <div>
                            <x-input-label for="name" :value="__('Nama Ruangan')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $room->name)" required autofocus />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="capacity" :value="__('Kapasitas (Orang)')" />
                            <x-text-input id="capacity" class="block mt-1 w-full" type="number" name="capacity" :value="old('capacity', $room->capacity)" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select name="status" id="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="available" @selected(old('status', $room->status) == 'available')>Tersedia (Available)</option>
                                <option value="maintenance" @selected(old('status', $room->status) == 'maintenance')>Maintenance</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="facilities" :value="__('Fasilitas (Pisahkan dengan koma)')" />
                            {{-- Konversi array kembali ke string untuk textarea --}}
                            <textarea id="facilities" name="facilities" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('facilities', implode(', ', $room->facilities ?? [])) }}</textarea>
                            <small class="text-gray-600">Contoh: AC, Proyektor, Papan Tulis</small>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.rooms.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Update') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> {{-- <-- HARUS JADI TAG PENUTUP --}}