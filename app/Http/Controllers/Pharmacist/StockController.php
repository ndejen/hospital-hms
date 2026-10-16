<?php

namespace App\Http\Controllers\Pharmacist;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $medicines  = Medicine::with('category')->where('is_active', true)->get();
        $lowStock   = $medicines->filter(fn($m) => $m->isLowStock());
        $expiring   = $medicines->filter(fn($m) => $m->isExpiringSoon());
        $expired    = $medicines->filter(fn($m) => $m->isExpired());
        return view('pharmacist.stock.index', compact('medicines', 'lowStock', 'expiring', 'expired'));
    }

    public function create()  { return view('pharmacist.stock.create'); }
    public function store(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity'    => 'required|integer|min:1',
        ]);
        Medicine::find($request->medicine_id)->increment('stock_quantity', $request->quantity);
        return back()->with('success', 'Stock updated successfully.');
    }

    public function show(Medicine $stock)  { return view('pharmacist.stock.show', compact('stock')); }
    public function edit(Medicine $stock)  { return view('pharmacist.stock.edit', compact('stock')); }
    public function update(Request $request, Medicine $stock)  { return back(); }
    public function destroy(Medicine $stock)  { return back(); }
}
