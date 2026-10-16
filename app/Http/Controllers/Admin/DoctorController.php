<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with(['user', 'department']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhere('doctor_number', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%");
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $doctors     = $query->latest()->paginate(15)->withQueryString();
        $departments = Department::active()->get();

        return view('admin.doctors.index', compact('doctors', 'departments'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        return view('admin.doctors.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:100',
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|min:8',
            'department_id'   => 'nullable|exists:departments,id',
            'specialization'  => 'required|string|max:100',
            'phone'           => 'nullable|string|max:20',
            'license_number'  => 'required|string|unique:doctors,license_number',
            'joining_date'    => 'nullable|date',
            'bio'             => 'nullable|string',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'doctor',
            'phone'    => $request->phone,
        ]);

        Doctor::create([
            'user_id'        => $user->id,
            'department_id'  => $request->department_id,
            'doctor_number'  => 'DOC-' . str_pad(Doctor::count() + 1, 4, '0', STR_PAD_LEFT),
            'specialization' => $request->specialization,
            'phone'          => $request->phone,
            'license_number' => $request->license_number,
            'joining_date'   => $request->joining_date,
            'bio'            => $request->bio,
        ]);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor created successfully.');
    }

    public function show(Doctor $doctor)
    {
        $doctor->load(['user', 'department', 'appointments.patient', 'prescriptions.patient']);
        return view('admin.doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        $departments = Department::where('is_active', true)->get();
        return view('admin.doctors.edit', compact('doctor', 'departments'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            'name'           => 'required|string|max:100',
            'email'          => ['required', 'email', Rule::unique('users', 'email')->ignore($doctor->user_id)],
            'department_id'  => 'nullable|exists:departments,id',
            'specialization' => 'required|string|max:100',
            'phone'          => 'nullable|string|max:20',
            'license_number' => ['required', 'string', Rule::unique('doctors', 'license_number')->ignore($doctor->id)],
            'joining_date'   => 'nullable|date',
            'bio'            => 'nullable|string',
            'availability'   => 'required|in:available,busy,off_duty',
        ]);

        $doctor->user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        $doctor->update([
            'department_id'  => $request->department_id,
            'specialization' => $request->specialization,
            'phone'          => $request->phone,
            'license_number' => $request->license_number,
            'joining_date'   => $request->joining_date,
            'bio'            => $request->bio,
            'availability'   => $request->availability,
        ]);

        return redirect()->route('admin.doctors.show', $doctor)
            ->with('success', 'Doctor updated successfully.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->user->delete();
        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor deleted successfully.');
    }
}
