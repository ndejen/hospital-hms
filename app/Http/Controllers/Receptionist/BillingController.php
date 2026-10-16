<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Invoice;

class BillingController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('patient')->latest()->paginate(15);
        return view('receptionist.billing.index', compact('invoices'));
    }

    public function show(Invoice $billing)
    {
        $billing->load(['patient', 'items', 'payments']);
        return view('receptionist.billing.show', compact('billing'));
    }
}
