<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaboratoryTest;
use Illuminate\Http\Request;

class LaboratoryController extends Controller
{
    public function index()
    {
        $tests = LaboratoryTest::latest()->paginate(15);
        return view('admin.laboratory.index', compact('tests'));
    }

    public function create()
    {
        return view('admin.laboratory.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:200',
            'category'         => 'nullable|string|max:100',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'normal_range'     => 'nullable|string|max:100',
            'unit'             => 'nullable|string|max:50',
            'turnaround_hours' => 'required|integer|min:1',
        ]);

        $validated['test_code'] = 'LAB-' . str_pad(LaboratoryTest::count() + 1, 4, '0', STR_PAD_LEFT);

        LaboratoryTest::create($validated);

        return redirect()->route('admin.lab.tests.index')
            ->with('success', 'Laboratory test added successfully.');
    }

    public function show(LaboratoryTest $test)
    {
        return view('admin.laboratory.show', compact('test'));
    }

    public function edit(LaboratoryTest $test)
    {
        return view('admin.laboratory.edit', compact('test'));
    }

    public function update(Request $request, LaboratoryTest $test)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:200',
            'category'         => 'nullable|string|max:100',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'normal_range'     => 'nullable|string|max:100',
            'unit'             => 'nullable|string|max:50',
            'turnaround_hours' => 'required|integer|min:1',
            'is_active'        => 'nullable|boolean',
        ]);

        $test->update($validated);

        return redirect()->route('admin.lab.tests.index')
            ->with('success', 'Test updated successfully.');
    }

    public function destroy(LaboratoryTest $test)
    {
        $test->delete();
        return redirect()->route('admin.lab.tests.index')
            ->with('success', 'Test deleted.');
    }
}
