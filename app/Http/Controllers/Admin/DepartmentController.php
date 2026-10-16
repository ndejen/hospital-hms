<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount(['doctors', 'nurses'])->latest()->paginate(15);
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'code'        => 'required|string|max:20|unique:departments,code',
            'description' => 'nullable|string',
            'head_doctor' => 'nullable|string|max:100',
            'phone'       => 'nullable|string|max:20',
            'location'    => 'nullable|string|max:100',
        ]);

        Department::create($validated);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function show(Department $department)
    {
        $department->load(['doctors.user', 'nurses.user', 'wards']);
        return view('admin.departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'code'        => 'required|string|max:20|unique:departments,code,' . $department->id,
            'description' => 'nullable|string',
            'head_doctor' => 'nullable|string|max:100',
            'phone'       => 'nullable|string|max:20',
            'location'    => 'nullable|string|max:100',
            'is_active'   => 'nullable|boolean',
        ]);

        $department->update($validated);

        return redirect()->route('admin.departments.show', $department)
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('admin.departments.index')
            ->with('success', 'Department deleted.');
    }
}
