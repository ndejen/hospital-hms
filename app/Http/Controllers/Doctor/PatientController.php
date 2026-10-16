<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $doctor = auth()->user()->doctor;

        $query = Patient::whereHas('appointments', fn($q) => $q->where('doctor_id', $doctor->id));

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) =>
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('patient_number', 'like', "%{$search}%")
            );
        }

        $patients = $query->latest()->paginate(15)->withQueryString();

        return view('doctor.patients.index', compact('patients'));
    }

    public function show(Patient $patient)
    {
        $patient->load([
            'medicalRecords.doctor',
            'prescriptions.items.medicine',
            'labRequests.tests',
            'labResults.test',
            'vitalSigns',
            'diagnoses',
        ]);

        return view('doctor.patients.show', compact('patient'));
    }
}
