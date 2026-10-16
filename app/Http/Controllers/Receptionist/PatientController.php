<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query();
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) =>
                $q->where('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name', 'like', "%{$s}%")
                  ->orWhere('patient_number', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
            );
        }
        $patients = $query->latest()->paginate(15)->withQueryString();
        return view('receptionist.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('receptionist.patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'gender'         => 'required|in:male,female,other',
            'date_of_birth'  => 'required|date|before:today',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email',
            'address'        => 'nullable|string',
            'blood_group'    => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'emergency_contact_name'  => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
        ]);
        $validated['patient_number'] = 'PAT-' . str_pad(Patient::count() + 1, 5, '0', STR_PAD_LEFT);
        Patient::create($validated);
        return redirect()->route('receptionist.patients.index')->with('success', 'Patient registered.');
    }

    public function show(Patient $patient)
    {
        $patient->load(['appointments.doctor.user', 'invoices']);
        return view('receptionist.patients.show', compact('patient'));
    }

    public function edit(Patient $patient) { return view('receptionist.patients.edit', compact('patient')); }

    public function update(Request $request, Patient $patient)
    {
        $patient->update($request->validate([
            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
            'phone'        => 'nullable|string|max:20',
            'email'        => 'nullable|email',
            'address'      => 'nullable|string',
        ]));
        return back()->with('success', 'Patient updated.');
    }

    public function destroy(Patient $patient) { $patient->delete(); return redirect()->route('receptionist.patients.index'); }
}
