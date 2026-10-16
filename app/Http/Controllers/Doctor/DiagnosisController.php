<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Diagnosis;
use App\Models\Patient;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    public function index()
    {
        $doctor = auth()->user()->doctor;
        $diagnoses = Diagnosis::with('patient')
            ->where('doctor_id', $doctor->id)
            ->latest()
            ->paginate(15);
        return view('doctor.diagnoses.index', compact('diagnoses'));
    }

    public function create()
    {
        $doctor   = auth()->user()->doctor;
        $patients = Patient::whereHas('appointments', fn($q) => $q->where('doctor_id', $doctor->id))->get();
        return view('doctor.diagnoses.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'      => 'required|exists:patients,id',
            'icd_code'        => 'nullable|string|max:20',
            'diagnosis_name'  => 'required|string|max:200',
            'description'     => 'nullable|string',
            'severity'        => 'required|in:mild,moderate,severe',
            'diagnosed_date'  => 'required|date',
            'is_chronic'      => 'nullable|boolean',
        ]);

        $validated['doctor_id'] = auth()->user()->doctor->id;

        Diagnosis::create($validated);

        return redirect()->route('doctor.diagnoses.index')
            ->with('success', 'Diagnosis recorded successfully.');
    }

    public function show(Diagnosis $diagnosis)
    {
        return view('doctor.diagnoses.show', compact('diagnosis'));
    }

    public function edit(Diagnosis $diagnosis)
    {
        return view('doctor.diagnoses.edit', compact('diagnosis'));
    }

    public function update(Request $request, Diagnosis $diagnosis)
    {
        $validated = $request->validate([
            'diagnosis_name' => 'required|string|max:200',
            'description'    => 'nullable|string',
            'severity'       => 'required|in:mild,moderate,severe',
            'is_chronic'     => 'nullable|boolean',
        ]);
        $diagnosis->update($validated);
        return back()->with('success', 'Diagnosis updated.');
    }

    public function destroy(Diagnosis $diagnosis)
    {
        $diagnosis->delete();
        return redirect()->route('doctor.diagnoses.index')->with('success', 'Diagnosis deleted.');
    }
}
