<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Motorcycle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MotorcycleController extends Controller
{
    // Tabel jenis motor + jumlah unit
    public function index()
    {
        $motorcycles = Motorcycle::withCount('units')->orderBy('brand')->orderBy('model')->paginate(10);
        return view('admin.motorcycles.index', compact('motorcycles'));
    }

    public function create()
    {
        return view('admin.motorcycles.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateMotorcycle($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('motorcycles', 'public');
        }

        Motorcycle::create($data);

        return redirect()->route('admin.motorcycles.index')
            ->with('success', 'Jenis motor berhasil ditambahkan.');
    }

    public function edit(Motorcycle $motorcycle)
    {
        return view('admin.motorcycles.edit', compact('motorcycle'));
    }

    public function update(Request $request, Motorcycle $motorcycle)
    {
        $data = $this->validateMotorcycle($request);

        if ($request->hasFile('image')) {
            $this->deleteImage($motorcycle);
            $data['image'] = $request->file('image')->store('motorcycles', 'public');
        }

        $motorcycle->update($data);

        return redirect()->route('admin.motorcycles.index')
            ->with('success', 'Jenis motor berhasil diperbarui.');
    }

    public function destroy(Motorcycle $motorcycle)
    {
        // Sesuai aturan RESTRICT: tidak boleh dihapus kalau masih punya unit
        if ($motorcycle->units()->exists()) {
            return redirect()->route('admin.motorcycles.index')
                ->with('error', 'Jenis motor ini masih punya unit. Hapus unitnya dulu.');
        }

        $this->deleteImage($motorcycle);
        $motorcycle->delete();

        return redirect()->route('admin.motorcycles.index')
            ->with('success', 'Jenis motor berhasil dihapus.');
    }

    private function validateMotorcycle(Request $request)
    {
        return $request->validate([
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'category' => 'required|in:matic,manual,sport',
            'cc' => 'required|integer|min:50|max:2000',
            'daily_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);
    }

    private function deleteImage(Motorcycle $motorcycle)
    {
        if ($motorcycle->image) {
            Storage::disk('public')->delete($motorcycle->image);
        }
    }
}