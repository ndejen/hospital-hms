<?php

namespace App\Http\Controllers\Pharmacist;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\Medicine;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Prescription::with(['patient', 'doctor.user', 'items.medicine']);
        if ($request->filled('status')) $query->where('status', $request->status);
        $prescriptions = $query->latest()->paginate(15)->withQueryString();
        return view('pharmacist.prescriptions.index', compact('prescriptions'));
    }

    public function create()  { return view('pharmacist.prescriptions.create'); }
    public function store(Request $request)  { return back(); }

    public function show(Prescription $prescription)
    {
        $prescription->load(['patient', 'doctor.user', 'items.medicine']);
        return view('pharmacist.prescriptions.show', compact('prescription'));
    }

    public function edit(Prescription $prescription)  { return view('pharmacist.prescriptions.edit', compact('prescription')); }
    public function update(Request $request, Prescription $prescription)  { return back(); }
    public function destroy(Prescription $prescription)  { return back(); }

    public function dispense(Request $request, Prescription $prescription)
    {
        foreach ($prescription->items as $item) {
            $medicine = Medicine::find($item->medicine_id);
            if ($medicine && $medicine->stock_quantity >= $item->quantity) {
                $medicine->decrement('stock_quantity', $item->quantity);
                $item->update(['is_dispensed' => true]);
            }
        }

        $prescription->update(['status' => 'dispensed']);

        return back()->with('success', 'Prescription dispensed successfully.');
    }
}
