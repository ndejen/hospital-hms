<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('patient_number', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }

        $patients = $query->latest()->paginate(15)->withQueryString();

        return view('admin.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('admin.patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'                  => 'required|string|max:100',
            'last_name'                   => 'required|string|max:100',
            'gender'                      => 'required|in:male,female,other',
            'date_of_birth'               => 'required|date|before:today',
            'phone'                       => 'nullable|string|max:20',
            'email'                       => 'nullable|email|max:100',
            'address'                     => 'nullable|string',
            'city'                        => 'nullable|string|max:100',
            'state'                       => 'nullable|string|max:100',
            'zip_code'                    => 'nullable|string|max:20',
            'emergency_contact_name'      => 'nullable|string|max:100',
            'emergency_contact_phone'     => 'nullable|string|max:20',
            'emergency_contact_relation'  => 'nullable|string|max:50',
            'blood_group'                 => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'occupation'                  => 'nullable|string|max:100',
            'known_allergies'             => 'nullable|string',
            'chronic_diseases'            => 'nullable|string',
            'create_portal_account'       => 'nullable|boolean',
        ]);

        // Generate patient number
        $validated['patient_number'] = 'PAT-' . str_pad(Patient::count() + 1, 5, '0', STR_PAD_LEFT);

        // Create portal account if requested
        if ($request->boolean('create_portal_account') && $request->filled('email')) {
            $user = User::create([
                'name'     => $request->first_name . ' ' . $request->last_name,
                'email'    => $request->email,
                'password' => Hash::make('Password@123'),
                'role'     => 'patient',
            ]);
            $validated['user_id'] = $user->id;
        }

        Patient::create($validated);

        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient registered successfully.');
    }

    public function show(Patient $patient)
    {
        $patient->load([
            'appointments.doctor',
            'medicalRecords.doctor',
            'prescriptions.items.medicine',
            'labRequests.tests',
            'labResults.test',
            'vitalSigns',
            'admissions.ward',
            'invoices',
        ]);

        return view('admin.patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('admin.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'first_name'                  => 'required|string|max:100',
            'last_name'                   => 'required|string|max:100',
            'gender'                      => 'required|in:male,female,other',
            'date_of_birth'               => 'required|date|before:today',
            'phone'                       => 'nullable|string|max:20',
            'email'                       => 'nullable|email|max:100',
            'address'                     => 'nullable|string',
            'city'                        => 'nullable|string|max:100',
            'state'                       => 'nullable|string|max:100',
            'zip_code'                    => 'nullable|string|max:20',
            'emergency_contact_name'      => 'nullable|string|max:100',
            'emergency_contact_phone'     => 'nullable|string|max:20',
            'emergency_contact_relation'  => 'nullable|string|max:50',
            'blood_group'                 => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'occupation'                  => 'nullable|string|max:100',
            'known_allergies'             => 'nullable|string',
            'chronic_diseases'            => 'nullable|string',
        ]);

        $patient->update($validated);

        return redirect()->route('admin.patients.show', $patient)
            ->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient deleted successfully.');
    }
}
