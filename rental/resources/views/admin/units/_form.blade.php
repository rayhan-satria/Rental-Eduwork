<div>
    <label for="motorcycle_id" class="block text-sm font-medium text-gray-700">Jenis Motor</label>
    <select id="motorcycle_id" name="motorcycle_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        <option value="">-- Pilih jenis motor --</option>
        @foreach ($motorcycles as $motorcycle)
            <option value="{{ $motorcycle->id }}"
                @selected(old('motorcycle_id', $unit->motorcycle_id ?? '') == $motorcycle->id)>
                {{ $motorcycle->full_name }} ({{ $motorcycle->cc }} cc)
            </option>
        @endforeach
    </select>
    @error('motorcycle_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div class="grid grid-cols-3 gap-4">
    <div>
        <label for="license_plate" class="block text-sm font-medium text-gray-700">Plat Nomor</label>
        <input id="license_plate" name="license_plate" type="text" placeholder="DK 1234 AB"
               value="{{ old('license_plate', $unit->license_plate ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm uppercase">
        @error('license_plate') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="year" class="block text-sm font-medium text-gray-700">Tahun</label>
        <input id="year" name="year" type="number" min="1990" max="{{ date('Y') + 1 }}" placeholder="{{ date('Y') }}"
               value="{{ old('year', $unit->year ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        @error('year') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="color" class="block text-sm font-medium text-gray-700">Warna</label>
        <input id="color" name="color" type="text" placeholder="Hitam"
               value="{{ old('color', $unit->color ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        @error('color') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label for="unit_condition" class="block text-sm font-medium text-gray-700">Kondisi</label>
                <select id="unit_condition" name="unit_condition"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @foreach (\App\Models\MotorcycleUnit::CONDITIONS as $value => $label)
                <option value="{{ $value }}" @selected(old('unit_condition', $unit->unit_condition ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        
        @error('unit_condition') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
        <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            @foreach (\App\Models\MotorcycleUnit::STATUSES as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $unit->status ?? 'available') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>