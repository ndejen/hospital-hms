<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nurse;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class NurseController extends Controller
{
    public function index()
    {
        $nurses = Nurse::with(['user', 'department'])->latest()->paginate(15);
        return view('admin.nurses.index', compact('nurses'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        return view('admin.nurses.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:100',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|min:8',
            'department_id'  => 'nullable|exists:departments,id',
            'specialization' => 'nullable|string|max:100',
            'phone'          => 'nullable|string|max:20',
            'license_number' => 'required|string|unique:nurses,license_number',
            'joining_date'   => 'nullable|date',
            'shift'          => 'required|in:morning,afternoon,night',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'nurse',
            'phone'    => $request->phone,
        ]);

        Nurse::create([
            'user_id'        => $user->id,
            'department_id'  => $request->department_id,
            'nurse_number'   => 'NUR-' . str_pad(Nurse::count() + 1, 4, '0', STR_PAD_LEFT),
            'specialization' => $request->specialization,
            'phone'          => $request->phone,
            'license_number' => $request->license_number,
            'joining_date'   => $request->joining_date,
            'shift'          => $request->shift,
        ]);

        return redirect()->route('admin.nurses.index')
            ->with('success', 'Nurse created successfully.');
    }

    public function show(Nurse $nurse)
    {
        $nurse->load(['user', 'department']);
        return view('admin.nurses.show', compact('nurse'));
    }

    public function edit(Nurse $nurse)
    {
        $departments = Department::where('is_active', true)->get();
        return view('admin.nurses.edit', compact('nurse', 'departments'));
    }

    public function update(Request $request, Nurse $nurse)
    {
        $request->validate([
            'name'           => 'required|string|max:100',
            'email'          => ['required', 'email', Rule::unique('users', 'email')->ignore($nurse->user_id)],
            'department_id'  => 'nullable|exists:departments,id',
            'specialization' => 'nullable|string|max:100',
            'phone'          => 'nullable|string|max:20',
            'license_number' => ['required', 'string', Rule::unique('nurses', 'license_number')->ignore($nurse->id)],
            'shift'          => 'required|in:morning,afternoon,night',
        ]);

        $nurse->user->update(['name' => $request->name, 'email' => $request->email]);
        $nurse->update($request->only('department_id', 'specialization', 'phone', 'license_number', 'joining_date', 'shift'));

        return redirect()->route('admin.nurses.show', $nurse)
            ->with('success', 'Nurse updated successfully.');
    }

    public function destroy(Nurse $nurse)
    {
        $nurse->user->delete();
        return redirect()->route('admin.nurses.index')
            ->with('success', 'Nurse deleted.');
    }
}
