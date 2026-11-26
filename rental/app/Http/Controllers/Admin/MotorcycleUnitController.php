<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Motorcycle;
use App\Models\MotorcycleUnit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MotorcycleUnitController extends Controller
{
    // Tabel unit motor
       public function index(Request $request)
    {
        $status = $request->query('status');

        $units = MotorcycleUnit::with('motorcycle')
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.units.index', compact('units', 'status'));
    }

    public function create()
    {
        $motorcycles = Motorcycle::orderBy('brand')->orderBy('model')->get();
        return view('admin.units.create', compact('motorcycles'));
    }

    public function store(Request $request)
    {
        $data = $this->validateUnit($request);

        MotorcycleUnit::create($data);

        return redirect()->route('admin.motorcycle-units.index')
            ->with('success', 'Unit motor berhasil ditambahkan.');
    }

    public function edit(MotorcycleUnit $motorcycleUnit)
    {
        $motorcycles = Motorcycle::orderBy('brand')->orderBy('model')->get();
        return view('admin.units.edit', [
            'unit' => $motorcycleUnit,
            'motorcycles' => $motorcycles,
        ]);
    }

    public function update(Request $request, MotorcycleUnit $motorcycleUnit)
    {
        $data = $this->validateUnit($request, $motorcycleUnit);

        $motorcycleUnit->update($data);

        return redirect()->route('admin.motorcycle-units.index')
            ->with('success', 'Unit motor berhasil diperbarui.');
    }

    public function destroy(MotorcycleUnit $motorcycleUnit)
    {
        $motorcycleUnit->delete();

        return redirect()->route('admin.motorcycle-units.index')
            ->with('success', 'Unit motor berhasil dihapus.');
    }

    private function validateUnit(Request $request, ?MotorcycleUnit $unit = null)
    {
        $data = $request->validate([
            'motorcycle_id' => 'required|exists:motorcycles,id',
            'license_plate' => [
                'required', 'string', 'max:20',
                Rule::unique('motorcycle_units', 'license_plate')->ignore($unit?->id),
            ],
            'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'color' => 'required|string|max:50',
            'unit_condition' => ['required', Rule::in(array_keys(MotorcycleUnit::CONDITIONS))],
            'status' => ['required', Rule::in(array_keys(MotorcycleUnit::STATUSES))],
        ]);

        // Plat nomor selalu disimpan dengan huruf besar: "dk 1234 ab" → "DK 1234 AB"
        $data['license_plate'] = strtoupper($data['license_plate']);

        return $data;
    }
}