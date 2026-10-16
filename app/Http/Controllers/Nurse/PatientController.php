<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) =>
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('patient_number', 'like', "%{$search}%")
            );
        }
        $patients = $query->where('is_active', true)->latest()->paginate(15)->withQueryString();
        return view('nurse.patients.index', compact('patients'));
    }

    public function show(Patient $patient)
    {
        $patient->load(['vitalSigns', 'admissions.ward', 'medicalRecords']);
        return view('nurse.patients.show', compact('patient'));
    }
}
