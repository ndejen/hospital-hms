<?php

namespace App\Http\Controllers\Admin;

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
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('medicine_code', 'like', "%{$search}%")
                  ->orWhere('generic_name', 'like', "%{$search}%");
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->whereRaw('stock_quantity <= reorder_level');
            } elseif ($request->stock_status === 'expired') {
                $query->where('expiry_date', '<', today());
            } elseif ($request->stock_status === 'expiring_soon') {
                $query->whereBetween('expiry_date', [today(), today()->addDays(30)]);
            }
        }

        $medicines   = $query->latest()->paginate(15)->withQueryString();
        $categories  = MedicineCategory::all();
        $lowStockCount = Medicine::whereRaw('stock_quantity <= reorder_level')->count();
        $expiredCount  = Medicine::where('expiry_date', '<', today())->count();

        return view('admin.medicines.index', compact('medicines', 'categories', 'lowStockCount', 'expiredCount'));
    }

    public function categories()
    {
        $categories = MedicineCategory::withCount('medicines')->get();
        return view('admin.medicines.categories', compact('categories'));
    }

    public function create()
    {
        $categories = MedicineCategory::all();
        return view('admin.medicines.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'   => 'nullable|exists:medicine_categories,id',
            'name'          => 'required|string|max:200',
            'generic_name'  => 'nullable|string|max:200',
            'manufacturer'  => 'nullable|string|max:100',
            'dosage_form'   => 'nullable|string|max:50',
            'strength'      => 'nullable|string|max:50',
            'unit'          => 'required|string|max:20',
            'stock_quantity'=> 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'unit_price'    => 'required|numeric|min:0',
            'expiry_date'   => 'nullable|date',
            'description'   => 'nullable|string',
        ]);

        $validated['medicine_code'] = 'MED-' . str_pad(Medicine::count() + 1, 5, '0', STR_PAD_LEFT);

        Medicine::create($validated);

        return redirect()->route('admin.medicines.index')
            ->with('success', 'Medicine added successfully.');
    }

    public function show(Medicine $medicine)
    {
        $medicine->load('category', 'prescriptionItems.prescription.patient');
        return view('admin.medicines.show', compact('medicine'));
    }

    public function edit(Medicine $medicine)
    {
        $categories = MedicineCategory::all();
        return view('admin.medicines.edit', compact('medicine', 'categories'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'category_id'   => 'nullable|exists:medicine_categories,id',
            'name'          => 'required|string|max:200',
            'generic_name'  => 'nullable|string|max:200',
            'manufacturer'  => 'nullable|string|max:100',
            'dosage_form'   => 'nullable|string|max:50',
            'strength'      => 'nullable|string|max:50',
            'unit'          => 'required|string|max:20',
            'stock_quantity'=> 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'unit_price'    => 'required|numeric|min:0',
            'expiry_date'   => 'nullable|date',
            'description'   => 'nullable|string',
            'is_active'     => 'nullable|boolean',
        ]);

        $medicine->update($validated);

        return redirect()->route('admin.medicines.show', $medicine)
            ->with('success', 'Medicine updated successfully.');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return redirect()->route('admin.medicines.index')
            ->with('success', 'Medicine deleted.');
    }
}
