<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Patient;
use App\Models\Payment;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('patient');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('patient', fn($q) =>
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                  );
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->latest()->paginate(15)->withQueryString();

        $summary = [
            'total_billed'  => Invoice::sum('total'),
            'total_paid'    => Invoice::sum('amount_paid'),
            'total_pending' => Invoice::whereIn('status', ['draft', 'sent', 'partial'])->sum('balance_due'),
        ];

        return view('admin.billing.index', compact('invoices', 'summary'));
    }

    public function create()
    {
        $patients = Patient::where('is_active', true)->get();
        return view('admin.billing.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'   => 'required|exists:patients,id',
            'invoice_date' => 'required|date',
            'due_date'     => 'nullable|date',
            'notes'        => 'nullable|string',
            'items'        => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.item_type'   => 'required|string',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.unit_price'  => 'required|numeric|min:0',
        ]);

        $subtotal = collect($request->items)->sum(fn($i) => $i['quantity'] * $i['unit_price']);
        $tax      = $subtotal * 0.05; // 5% tax
        $discount = $request->discount ?? 0;
        $total    = $subtotal + $tax - $discount;

        $invoice = Invoice::create([
            'patient_id'    => $request->patient_id,
            'invoice_number'=> 'INV-' . str_pad(Invoice::count() + 1, 6, '0', STR_PAD_LEFT),
            'invoice_date'  => $request->invoice_date,
            'due_date'      => $request->due_date,
            'subtotal'      => $subtotal,
            'tax'           => $tax,
            'discount'      => $discount,
            'total'         => $total,
            'balance_due'   => $total,
            'notes'         => $request->notes,
            'status'        => 'draft',
        ]);

        foreach ($request->items as $item) {
            $invoice->items()->create([
                'description' => $item['description'],
                'item_type'   => $item['item_type'],
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['unit_price'],
                'total'       => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return redirect()->route('admin.billing.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $billing)
    {
        $billing->load(['patient', 'items', 'payments', 'appointment.doctor']);
        return view('admin.billing.show', compact('billing'));
    }

    public function edit(Invoice $billing)
    {
        $patients = Patient::where('is_active', true)->get();
        return view('admin.billing.edit', compact('billing', 'patients'));
    }

    public function update(Request $request, Invoice $billing)
    {
        $request->validate([
            'status'   => 'required|in:draft,sent,partial,paid,overdue,cancelled',
            'notes'    => 'nullable|string',
        ]);

        // Handle payment
        if ($request->filled('payment_amount')) {
            $request->validate([
                'payment_amount' => 'numeric|min:0.01',
                'payment_method' => 'required|string',
            ]);

            Payment::create([
                'invoice_id'     => $billing->id,
                'patient_id'     => $billing->patient_id,
                'payment_number' => 'PAY-' . str_pad(Payment::count() + 1, 6, '0', STR_PAD_LEFT),
                'amount'         => $request->payment_amount,
                'method'         => $request->payment_method,
                'payment_date'   => now(),
                'notes'          => $request->payment_notes,
            ]);

            $totalPaid = $billing->payments()->sum('amount') + $request->payment_amount;
            $billing->update([
                'amount_paid' => $totalPaid,
                'balance_due' => $billing->total - $totalPaid,
                'status'      => $totalPaid >= $billing->total ? 'paid' : 'partial',
            ]);
        } else {
            $billing->update([
                'status' => $request->status,
                'notes'  => $request->notes,
            ]);
        }

        return back()->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $billing)
    {
        $billing->delete();
        return redirect()->route('admin.billing.index')
            ->with('success', 'Invoice deleted.');
    }

    public function print(Invoice $billing)
    {
        $billing->load(['patient', 'items', 'payments']);
        return view('admin.billing.print', compact('billing'));
    }
}
