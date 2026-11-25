<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Unit Motor</h2>
            <a href="{{ route('admin.motorcycle-units.create') }}"
               class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">
                + Tambah Unit
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Plat Nomor</th>
                            <th class="px-4 py-3">Jenis Motor</th>
                            <th class="px-4 py-3">Tahun</th>
                            <th class="px-4 py-3">Warna</th>
                            <th class="px-4 py-3">Kondisi</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($units as $unit)
                            @php
                                $badgeClass = match ($unit->status) {
                                    'available' => 'bg-green-100 text-green-800',
                                    'rented' => 'bg-blue-100 text-blue-800',
                                    'maintenance' => 'bg-yellow-100 text-yellow-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <tr>
                                <td class="px-4 py-3 font-medium whitespace-nowrap">{{ $unit->license_plate }}</td>
                                <td class="px-4 py-3">{{ $unit->motorcycle->full_name }}</td>
                                <td class="px-4 py-3">{{ $unit->year }}</td>
                                <td class="px-4 py-3">{{ $unit->color }}</td>
                                <td class="px-4 py-3">{{ $unit->unit_condition }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">
                                        {{ \App\Models\MotorcycleUnit::STATUSES[$unit->status] ?? $unit->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-3">
                                        <a href="{{ route('admin.motorcycle-units.edit', $unit) }}"
                                           class="text-yellow-600 hover:underline">Edit</a>
                                        <form method="POST" action="{{ route('admin.motorcycle-units.destroy', $unit) }}"
                                              onsubmit="return confirm('Hapus unit {{ $unit->license_plate }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-gray-500">Belum ada unit motor.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $units->links() }}</div>
        </div>
    </div>
</x-app-layout>