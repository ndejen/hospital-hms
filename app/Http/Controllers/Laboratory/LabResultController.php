<?php

namespace App\Http\Controllers\Laboratory;

use App\Http\Controllers\Controller;
use App\Models\LabResult;
use App\Models\LabRequest;
use App\Models\LaboratoryTest;
use Illuminate\Http\Request;

class LabResultController extends Controller
{
    public function index()
    {
        $results = LabResult::with(['labRequest.patient', 'test'])->latest()->paginate(15);
        return view('laboratory.results.index', compact('results'));
    }

    public function create()
    {
        $requests = LabRequest::with(['patient', 'tests'])->whereIn('status', ['collected', 'processing'])->get();
        $tests    = LaboratoryTest::where('is_active', true)->get();
        return view('laboratory.results.create', compact('requests', 'tests'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lab_request_id'     => 'required|exists:lab_requests,id',
            'laboratory_test_id' => 'required|exists:laboratory_tests,id',
            'result_value'       => 'required|string',
            'unit'               => 'nullable|string',
            'normal_range'       => 'nullable|string',
            'interpretation'     => 'required|in:normal,low,high,critical',
            'notes'              => 'nullable|string',
        ]);

        $labRequest = LabRequest::find($request->lab_request_id);

        $validated['patient_id']  = $labRequest->patient_id;
        $validated['performed_by'] = auth()->id();
        $validated['performed_at'] = now();

        $result = LabResult::create($validated);

        // Update request status to processing/completed
        $labRequest->update(['status' => 'processing']);

        return redirect()->route('laboratory.results.show', $result)
            ->with('success', 'Result recorded successfully.');
    }

    public function show(LabResult $result)
    {
        $result->load(['labRequest.patient', 'test', 'performedBy']);
        return view('laboratory.results.show', compact('result'));
    }

    public function edit(LabResult $result)
    {
        return view('laboratory.results.edit', compact('result'));
    }

    public function update(Request $request, LabResult $result)
    {
        $validated = $request->validate([
            'result_value'   => 'required|string',
            'interpretation' => 'required|in:normal,low,high,critical',
            'notes'          => 'nullable|string',
            'is_verified'    => 'nullable|boolean',
        ]);
        $result->update($validated);
        return back()->with('success', 'Result updated.');
    }

    public function destroy(LabResult $result)
    {
        $result->delete();
        return redirect()->route('laboratory.results.index')->with('success', 'Result deleted.');
    }

    public function print(LabResult $result)
    {
        $result->load(['labRequest.patient', 'test', 'performedBy']);
        return view('laboratory.results.print', compact('result'));
    }
}
