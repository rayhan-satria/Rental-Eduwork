<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Armada Motor</h2>
            <a href="{{ route('admin.motorcycles.create') }}"
               class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">
                + Tambah Jenis Motor
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md">{{ session('error') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Foto</th>
                            <th class="px-4 py-3">Motor</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">CC</th>
                            <th class="px-4 py-3">Harga / 24 jam</th>
                            <th class="px-4 py-3">Jumlah Unit</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($motorcycles as $motorcycle)
                            <tr>
                                <td class="px-4 py-3">
                                    @if ($motorcycle->image_url)
                                        <img src="{{ $motorcycle->image_url }}" alt="{{ $motorcycle->full_name }}"
                                             class="w-16 h-12 object-cover rounded">
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-medium">{{ $motorcycle->full_name }}</td>
                                <td class="px-4 py-3 capitalize">{{ $motorcycle->category }}</td>
                                <td class="px-4 py-3">{{ $motorcycle->cc }} cc</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    Rp {{ number_format($motorcycle->daily_price, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3">{{ $motorcycle->units_count }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-3">
                                        <a href="{{ route('admin.motorcycles.edit', $motorcycle) }}"
                                           class="text-yellow-600 hover:underline">Edit</a>
                                        <form method="POST" action="{{ route('admin.motorcycles.destroy', $motorcycle) }}"
                                              onsubmit="return confirm('Hapus jenis motor ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-gray-500">Belum ada data motor.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $motorcycles->links() }}</div>
        </div>
    </div>
</x-app-layout>