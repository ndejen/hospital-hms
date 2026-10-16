<?php

namespace App\Http\Controllers\Laboratory;

use App\Http\Controllers\Controller;
use App\Models\LabRequest;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $query = LabRequest::with(['patient', 'doctor.user', 'tests']);
        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('priority')) $query->where('priority', $request->priority);
        $requests = $query->orderByRaw("FIELD(priority, 'stat', 'urgent', 'routine')")->latest()->paginate(15)->withQueryString();
        return view('laboratory.tests.index', compact('requests'));
    }

    public function create()  { return view('laboratory.tests.create'); }
    public function store(Request $request)  { return back(); }

    public function show(LabRequest $test)
    {
        $test->load(['patient', 'doctor.user', 'tests', 'results.test']);
        return view('laboratory.tests.show', compact('test'));
    }

    public function edit(LabRequest $test)  { return view('laboratory.tests.edit', compact('test')); }

    public function update(Request $request, LabRequest $test)
    {
        $test->update($request->validate(['status' => 'required|in:pending,collected,processing,completed,cancelled']));
        return back()->with('success', 'Status updated.');
    }

    public function destroy(LabRequest $test) { return back(); }
}
