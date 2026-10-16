<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Ward;
use App\Models\Bed;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Admission::with(['patient', 'doctor.user', 'ward', 'bed']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $admissions = $query->latest('admission_date')->paginate(15)->withQueryString();
        return view('admin.admissions.index', compact('admissions'));
    }

    public function create()
    {
        $patients = Patient::where('is_active', true)->get();
        $doctors  = Doctor::with('user')->where('is_active', true)->get();
        $wards    = Ward::with(['beds' => fn($q) => $q->where('status', 'available')])
                        ->where('is_active', true)->get();
        return view('admin.admissions.create', compact('patients', 'doctors', 'wards'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'      => 'required|exists:patients,id',
            'doctor_id'       => 'required|exists:doctors,id',
            'ward_id'         => 'nullable|exists:wards,id',
            'bed_id'          => 'nullable|exists:beds,id',
            'admission_date'  => 'required|date',
            'admission_reason'=> 'required|string',
        ]);

        $validated['admission_number'] = 'ADM-' . str_pad(Admission::count() + 1, 6, '0', STR_PAD_LEFT);
        $validated['status'] = 'admitted';

        $admission = Admission::create($validated);

        // Mark bed as occupied
        if ($request->bed_id) {
            Bed::find($request->bed_id)->update(['status' => 'occupied']);
        }

        return redirect()->route('admin.admissions.show', $admission)
            ->with('success', 'Patient admitted successfully.');
    }

    public function show(Admission $admission)
    {
        $admission->load(['patient', 'doctor.user', 'ward', 'bed', 'nursingNotes.nurse.user']);
        return view('admin.admissions.show', compact('admission'));
    }

    public function edit(Admission $admission)
    {
        $patients = Patient::where('is_active', true)->get();
        $doctors  = Doctor::with('user')->where('is_active', true)->get();
        $wards    = Ward::where('is_active', true)->get();
        return view('admin.admissions.edit', compact('admission', 'patients', 'doctors', 'wards'));
    }

    public function update(Request $request, Admission $admission)
    {
        $validated = $request->validate([
            'status'             => 'required|in:admitted,discharged,transferred',
            'discharge_date'     => 'nullable|date',
            'discharge_summary'  => 'nullable|string',
            'discharge_condition'=> 'nullable|in:improved,recovered,referred,deceased,lama',
        ]);

        if ($validated['status'] === 'discharged') {
            $validated['discharge_date'] = $validated['discharge_date'] ?? now();
            // Free up the bed
            if ($admission->bed_id) {
                Bed::find($admission->bed_id)->update(['status' => 'available']);
            }
        }

        $admission->update($validated);

        return redirect()->route('admin.admissions.show', $admission)
            ->with('success', 'Admission updated successfully.');
    }

    public function destroy(Admission $admission)
    {
        if ($admission->bed_id) {
            Bed::find($admission->bed_id)->update(['status' => 'available']);
        }
        $admission->delete();
        return redirect()->route('admin.admissions.index')
            ->with('success', 'Admission deleted.');
    }
}
