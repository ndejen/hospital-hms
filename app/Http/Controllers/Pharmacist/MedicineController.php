<?php

namespace App\Http\Controllers\Pharmacist;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::with('category');
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('name', 'like', "%{$s}%")->orWhere('medicine_code', 'like', "%{$s}%");
        }
        $medicines = $query->where('is_active', true)->latest()->paginate(15)->withQueryString();
        return view('pharmacist.medicines.index', compact('medicines'));
    }

    public function create()
    {
        $categories = MedicineCategory::all();
        return view('pharmacist.medicines.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:200',
            'dosage_form'   => 'nullable|string',
            'strength'      => 'nullable|string',
            'stock_quantity'=> 'required|integer|min:0',
            'unit_price'    => 'required|numeric|min:0',
            'expiry_date'   => 'nullable|date',
        ]);
        $validated['medicine_code'] = 'MED-' . str_pad(Medicine::count() + 1, 5, '0', STR_PAD_LEFT);
        $validated['reorder_level'] = $request->reorder_level ?? 10;
        $validated['unit'] = $request->unit ?? 'pcs';
        Medicine::create($validated);
        return redirect()->route('pharmacist.medicines.index')->with('success', 'Medicine added.');
    }

    public function show(Medicine $medicine)  { return view('pharmacist.medicines.show', compact('medicine')); }
    public function edit(Medicine $medicine)  { $categories = MedicineCategory::all(); return view('pharmacist.medicines.edit', compact('medicine', 'categories')); }

    public function update(Request $request, Medicine $medicine)
    {
        $medicine->update($request->validate([
            'stock_quantity'=> 'required|integer|min:0',
            'unit_price'    => 'required|numeric|min:0',
            'expiry_date'   => 'nullable|date',
        ]));
        return back()->with('success', 'Updated.');
    }

    public function destroy(Medicine $medicine) { $medicine->delete(); return redirect()->route('pharmacist.medicines.index'); }
}
