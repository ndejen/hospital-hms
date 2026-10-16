<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\LabRequest;
use App\Models\LaboratoryTest;
use App\Models\Patient;
use App\Models\LabResult;
use Illuminate\Http\Request;

class LabRequestController extends Controller
{
    public function index()
    {
        $doctor  = auth()->user()->doctor;
        $requests = LabRequest::with(['patient', 'tests'])
            ->where('doctor_id', $doctor->id)
            ->latest()
            ->paginate(15);
        return view('doctor.lab-requests.index', compact('requests'));
    }

    public function create()
    {
        $doctor   = auth()->user()->doctor;
        $patients = Patient::whereHas('appointments', fn($q) => $q->where('doctor_id', $doctor->id))->get();
        $tests    = LaboratoryTest::where('is_active', true)->get();
        return view('doctor.lab-requests.create', compact('patients', 'tests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id'     => 'required|exists:patients,id',
            'requested_date' => 'required|date',
            'priority'       => 'required|in:routine,urgent,stat',
            'clinical_notes' => 'nullable|string',
            'tests'          => 'required|array|min:1',
            'tests.*'        => 'exists:laboratory_tests,id',
        ]);

        $doctor  = auth()->user()->doctor;

        $labRequest = LabRequest::create([
            'patient_id'     => $request->patient_id,
            'doctor_id'      => $doctor->id,
            'request_number' => 'LAB-' . str_pad(LabRequest::count() + 1, 6, '0', STR_PAD_LEFT),
            'requested_date' => $request->requested_date,
            'priority'       => $request->priority,
            'clinical_notes' => $request->clinical_notes,
            'status'         => 'pending',
        ]);

        $labRequest->tests()->attach($request->tests);

        return redirect()->route('doctor.lab-requests.show', $labRequest)
            ->with('success', 'Lab request submitted successfully.');
    }

    public function show(LabRequest $labRequest)
    {
        $labRequest->load(['patient', 'doctor.user', 'tests', 'results.test']);
        return view('doctor.lab-requests.show', compact('labRequest'));
    }

    public function edit(LabRequest $labRequest)
    {
        $tests = LaboratoryTest::where('is_active', true)->get();
        return view('doctor.lab-requests.edit', compact('labRequest', 'tests'));
    }

    public function update(Request $request, LabRequest $labRequest)
    {
        $request->validate(['clinical_notes' => 'nullable|string', 'priority' => 'required|in:routine,urgent,stat']);
        $labRequest->update($request->only('priority', 'clinical_notes'));
        return back()->with('success', 'Request updated.');
    }

    public function destroy(LabRequest $labRequest)
    {
        $labRequest->delete();
        return redirect()->route('doctor.lab-requests.index')->with('success', 'Request deleted.');
    }

    public function results()
    {
        $doctor  = auth()->user()->doctor;
        $results = LabResult::with(['labRequest.patient', 'test'])
            ->whereHas('labRequest', fn($q) => $q->where('doctor_id', $doctor->id))
            ->where('is_verified', true)
            ->latest()
            ->paginate(15);
        return view('doctor.lab-results.index', compact('results'));
    }
}
