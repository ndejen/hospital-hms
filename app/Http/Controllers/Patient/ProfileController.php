<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $patient = auth()->user()->patient;
        return view('patient.profile.index', compact('patient'));
    }

    public function edit()
    {
        $patient = auth()->user()->patient;
        return view('patient.profile.edit', compact('patient'));
    }

    public function update(Request $request)
    {
        $patient = auth()->user()->patient;
        $validated = $request->validate([
            'phone'                       => 'nullable|string|max:20',
            'address'                     => 'nullable|string',
            'city'                        => 'nullable|string|max:100',
            'emergency_contact_name'      => 'nullable|string|max:100',
            'emergency_contact_phone'     => 'nullable|string|max:20',
            'emergency_contact_relation'  => 'nullable|string|max:50',
            'known_allergies'             => 'nullable|string',
        ]);
        $patient->update($validated);
        return redirect()->route('patient.profile.index')->with('success', 'Profile updated.');
    }
}
