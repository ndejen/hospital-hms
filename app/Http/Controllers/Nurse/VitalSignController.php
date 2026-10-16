<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\VitalSign;
use App\Models\Patient;
use Illuminate\Http\Request;

class VitalSignController extends Controller
{
    public function index()
    {
        $vitals = VitalSign::with('patient')->latest('recorded_at')->paginate(15);
        return view('nurse.vital-signs.index', compact('vitals'));
    }

    public function create()
    {
        $patients = Patient::where('is_active', true)->get();
        return view('nurse.vital-signs.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'temperature'      => 'nullable|numeric|between:30,45',
            'blood_pressure'   => 'nullable|string|max:20',
            'pulse_rate'       => 'nullable|integer|between:0,300',
            'respiratory_rate' => 'nullable|integer|between:0,100',
            'oxygen_saturation'=> 'nullable|numeric|between:0,100',
            'weight'           => 'nullable|numeric|between:0,500',
            'height'           => 'nullable|numeric|between:0,300',
            'notes'            => 'nullable|string',
            'recorded_at'      => 'required|date',
        ]);

        $validated['nurse_id'] = auth()->user()->nurse->id;

        // Auto-calculate BMI
        if (!empty($validated['weight']) && !empty($validated['height'])) {
            $heightM = $validated['height'] / 100;
            $validated['bmi'] = round($validated['weight'] / ($heightM * $heightM), 2);
        }

        VitalSign::create($validated);

        return redirect()->route('nurse.vital-signs.index')
            ->with('success', 'Vital signs recorded successfully.');
    }

    public function show(VitalSign $vitalSign)
    {
        return view('nurse.vital-signs.show', compact('vitalSign'));
    }

    public function edit(VitalSign $vitalSign)
    {
        $patients = Patient::where('is_active', true)->get();
        return view('nurse.vital-signs.edit', compact('vitalSign', 'patients'));
    }

    public function update(Request $request, VitalSign $vitalSign)
    {
        $validated = $request->validate([
            'temperature'      => 'nullable|numeric',
            'blood_pressure'   => 'nullable|string|max:20',
            'pulse_rate'       => 'nullable|integer',
            'respiratory_rate' => 'nullable|integer',
            'oxygen_saturation'=> 'nullable|numeric',
            'weight'           => 'nullable|numeric',
            'height'           => 'nullable|numeric',
            'notes'            => 'nullable|string',
        ]);
        $vitalSign->update($validated);
        return back()->with('success', 'Vital signs updated.');
    }

    public function destroy(VitalSign $vitalSign)
    {
        $vitalSign->delete();
        return redirect()->route('nurse.vital-signs.index')->with('success', 'Record deleted.');
    }
}
