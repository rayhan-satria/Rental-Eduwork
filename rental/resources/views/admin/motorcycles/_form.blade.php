<div class="grid grid-cols-2 gap-4">
    <div>
        <label for="brand" class="block text-sm font-medium text-gray-700">Merk</label>
        <input id="brand" name="brand" type="text" placeholder="Honda"
               value="{{ old('brand', $motorcycle->brand ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        @error('brand') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
        <input id="model" name="model" type="text" placeholder="Vario 160"
               value="{{ old('model', $motorcycle->model ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        @error('model') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="grid grid-cols-3 gap-4">
    <div>
        <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
        <select id="category" name="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">-- Pilih --</option>
            @foreach (['matic' => 'Matic', 'manual' => 'Manual', 'sport' => 'Sport'] as $value => $label)
                <option value="{{ $value }}" @selected(old('category', $motorcycle->category ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('category') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="cc" class="block text-sm font-medium text-gray-700">CC</label>
        <input id="cc" name="cc" type="number" min="50" placeholder="160"
               value="{{ old('cc', $motorcycle->cc ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        @error('cc') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="daily_price" class="block text-sm font-medium text-gray-700">Harga / 24 jam (Rp)</label>
        <input id="daily_price" name="daily_price" type="number" min="0" placeholder="100000"
               value="{{ old('daily_price', isset($motorcycle) ? (int) $motorcycle->daily_price : '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        @error('daily_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div>
    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
    <textarea id="description" name="description" rows="3" placeholder="Spesifikasi, fasilitas helm, jas hujan, dll."
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $motorcycle->description ?? '') }}</textarea>
    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="image" class="block text-sm font-medium text-gray-700">Foto</label>
    @if (isset($motorcycle) && $motorcycle->image_url)
        <img src="{{ $motorcycle->image_url }}" alt="{{ $motorcycle->full_name }}" class="mt-2 w-32 h-24 object-cover rounded">
        <p class="mt-1 text-xs text-gray-500">Kosongkan kalau tidak ingin mengganti foto.</p>
    @endif
    <input id="image" name="image" type="file" accept="image/*" class="mt-2 block w-full text-sm">
    @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>