<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Unit {{ $unit->license_plate }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.motorcycle-units.update', $unit) }}"
                  class="bg-white p-6 shadow-sm sm:rounded-lg space-y-4">
                @csrf
                @method('PUT')

                @include('admin.units._form')

                <div class="flex gap-3">
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md">Simpan Perubahan</button>
                    <a href="{{ route('admin.motorcycle-units.index') }}" class="px-4 py-2 text-gray-600">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>