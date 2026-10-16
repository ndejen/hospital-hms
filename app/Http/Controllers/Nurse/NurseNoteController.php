<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\NursingNote;
use App\Models\Patient;
use App\Models\Admission;
use Illuminate\Http\Request;

class NurseNoteController extends Controller
{
    public function index()
    {
        $nurse = auth()->user()->nurse;
        $notes = NursingNote::with('patient')->where('nurse_id', $nurse->id)->latest()->paginate(15);
        return view('nurse.notes.index', compact('notes'));
    }

    public function create()
    {
        $patients   = Patient::where('is_active', true)->get();
        $admissions = Admission::with('patient')->where('status', 'admitted')->get();
        return view('nurse.notes.create', compact('patients', 'admissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'   => 'required|exists:patients,id',
            'admission_id' => 'nullable|exists:admissions,id',
            'note'         => 'required|string',
            'noted_at'     => 'required|date',
        ]);
        $validated['nurse_id'] = auth()->user()->nurse->id;
        NursingNote::create($validated);
        return redirect()->route('nurse.nurse-notes.index')->with('success', 'Note saved.');
    }

    public function show(NursingNote $nurseNote)  { return view('nurse.notes.show', compact('nurseNote')); }
    public function edit(NursingNote $nurseNote)  { $patients = Patient::all(); return view('nurse.notes.edit', compact('nurseNote', 'patients')); }
    public function update(Request $request, NursingNote $nurseNote)
    {
        $nurseNote->update($request->validate(['note' => 'required|string']));
        return back()->with('success', 'Note updated.');
    }
    public function destroy(NursingNote $nurseNote)
    {
        $nurseNote->delete();
        return redirect()->route('nurse.nurse-notes.index')->with('success', 'Note deleted.');
    }
}
