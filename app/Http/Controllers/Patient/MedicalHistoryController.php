<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;

class MedicalHistoryController extends Controller
{
    public function index()
    {
        $patient = auth()->user()->patient;
        $records = $patient->medicalRecords()->with('doctor.user')->latest('visit_date')->paginate(10);
        return view('patient.medical-history.index', compact('records'));
    }

    public function show(MedicalRecord $medicalHistory)
    {
        $medicalHistory->load(['doctor.user', 'diagnoses', 'prescriptions.items.medicine']);
        return view('patient.medical-history.show', compact('medicalHistory'));
    }
}
