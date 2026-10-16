<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function index()
    {
        $admissions = Admission::with(['patient', 'ward', 'bed', 'doctor.user'])
            ->where('status', 'admitted')
            ->latest('admission_date')
            ->paginate(15);
        return view('nurse.admissions.index', compact('admissions'));
    }

    public function create()  { return view('nurse.admissions.create'); }
    public function store(Request $request)  { return back(); }
    public function show(Admission $admission)
    {
        $admission->load(['patient', 'ward', 'bed', 'doctor.user', 'nursingNotes.nurse.user']);
        return view('nurse.admissions.show', compact('admission'));
    }
    public function edit(Admission $admission) { return view('nurse.admissions.edit', compact('admission')); }
    public function update(Request $request, Admission $admission) { return back(); }
    public function destroy(Admission $admission) { return back(); }
}
