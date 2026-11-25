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
    public function index()
    {
        $units = MotorcycleUnit::with('motorcycle')->orderBy('license_plate')->paginate(10);
        return view('admin.units.index', compact('units'));
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
            'unit_condition' => 'required|string|max:50',
            'status' => ['required', Rule::in(array_keys(MotorcycleUnit::STATUSES))],
        ]);

        // Plat nomor selalu disimpan dengan huruf besar: "dk 1234 ab" → "DK 1234 AB"
        $data['license_plate'] = strtoupper($data['license_plate']);

        return $data;
    }
}