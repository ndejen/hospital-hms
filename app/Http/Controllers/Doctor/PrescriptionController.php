<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Patient;
use App\Models\Medicine;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index()
    {
        $doctor = auth()->user()->doctor;
        $prescriptions = Prescription::with(['patient', 'items.medicine'])
            ->where('doctor_id', $doctor->id)
            ->latest()
            ->paginate(15);
        return view('doctor.prescriptions.index', compact('prescriptions'));
    }

    public function create()
    {
        $doctor   = auth()->user()->doctor;
        $patients = Patient::whereHas('appointments', fn($q) => $q->where('doctor_id', $doctor->id))->get();
        $medicines = Medicine::where('is_active', true)->get();
        return view('doctor.prescriptions.create', compact('patients', 'medicines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id'     => 'required|exists:patients,id',
            'prescribed_date'=> 'required|date',
            'valid_until'    => 'nullable|date',
            'notes'          => 'nullable|string',
            'items'          => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.dosage'      => 'required|string',
            'items.*.frequency'   => 'required|string',
            'items.*.duration_days' => 'required|integer|min:1',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.instructions'=> 'nullable|string',
        ]);

        $doctor = auth()->user()->doctor;

        $prescription = Prescription::create([
            'patient_id'          => $request->patient_id,
            'doctor_id'           => $doctor->id,
            'prescription_number' => 'PRX-' . str_pad(Prescription::count() + 1, 6, '0', STR_PAD_LEFT),
            'prescribed_date'     => $request->prescribed_date,
            'valid_until'         => $request->valid_until,
            'notes'               => $request->notes,
            'status'              => 'pending',
        ]);

        foreach ($request->items as $item) {
            $prescription->items()->create($item);
        }

        return redirect()->route('doctor.prescriptions.show', $prescription)
            ->with('success', 'Prescription created successfully.');
    }

    public function show(Prescription $prescription)
    {
        $prescription->load(['patient', 'doctor.user', 'items.medicine']);
        return view('doctor.prescriptions.show', compact('prescription'));
    }

    public function edit(Prescription $prescription)
    {
        $medicines = Medicine::where('is_active', true)->get();
        return view('doctor.prescriptions.edit', compact('prescription', 'medicines'));
    }

    public function update(Request $request, Prescription $prescription)
    {
        $request->validate([
            'notes'      => 'nullable|string',
            'valid_until'=> 'nullable|date',
        ]);
        $prescription->update($request->only('notes', 'valid_until'));
        return back()->with('success', 'Prescription updated.');
    }

    public function destroy(Prescription $prescription)
    {
        $prescription->delete();
        return redirect()->route('doctor.prescriptions.index')
            ->with('success', 'Prescription deleted.');
    }
}
