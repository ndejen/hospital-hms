@extends('layouts.app')

@section('title', 'Pharmacist Dashboard')
@section('page-title', 'Pharmacist Dashboard')

@section('content')
{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-primary">
            <i class="fas fa-prescription stat-icon"></i>
            <p class="stat-number">{{ $stats['pending_prescriptions'] }}</p>
            <p class="stat-label">Pending Prescriptions</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-success">
            <i class="fas fa-check-circle stat-icon"></i>
            <p class="stat-number">{{ $stats['dispensed_today'] }}</p>
            <p class="stat-label">Dispensed Today</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-warning">
            <i class="fas fa-exclamation-triangle stat-icon"></i>
            <p class="stat-number">{{ $stats['low_stock'] }}</p>
            <p class="stat-label">Low Stock</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-bg-info">
            <i class="fas fa-calendar-times stat-icon"></i>
            <p class="stat-number">{{ $stats['expiring_soon'] }}</p>
            <p class="stat-label">Expiring Soon</p>
        </div>
    </div>
</div>

{{-- Pending Prescriptions --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between">
        <span><i class="fas fa-tasks me-2"></i>Pending Prescriptions</span>
        <a href="{{ route('pharmacist.prescriptions.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Prescription #</th><th>Patient</th><th>Doctor</th><th>Items</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($pendingPrescriptions as $prx)
                    <tr>
                        <td style="font-size:13px">{{ $prx->prescription_number }}</td>
                        <td>
                            <div class="fw-500" style="font-size:13px">{{ $prx->patient->full_name ?? 'N/A' }}</div>
                            <div style="font-size:11px;color:#6c757d">{{ $prx->patient->patient_number ?? '' }}</div>
                        </td>
                        <td style="font-size:13px">{{ $prx->doctor->full_name ?? 'N/A' }}</td>
                        <td style="font-size:13px">{{ $prx->items->count() }} items</td>
                        <td>
                            <a href="{{ route('pharmacist.prescriptions.show', $prx) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>View
                            </a>
                            <form method="POST" action="{{ route('pharmacist.prescriptions.dispense', $prx) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-success" onclick="return confirm('Dispense this prescription?')">
                                    <i class="fas fa-check me-1"></i>Dispense
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">No pending prescriptions</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Low Stock Alert --}}
    <div class="col-md-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Low Stock Alert</span>
                <a href="{{ route('pharmacist.stock.index') }}" class="btn btn-sm btn-outline-primary">Stock Management</a>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Medicine</th><th>Current Stock</th><th>Reorder Level</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($lowStockMedicines as $med)
                        <tr>
                            <td>
                                <div class="fw-500" style="font-size:13px">{{ $med->name }}</div>
                                <div style="font-size:11px;color:#6c757d">{{ $med->medicine_code }}</div>
                            </td>
                            <td style="font-size:13px"><span class="badge bg-danger">{{ $med->stock_quantity }}</span></td>
                            <td style="font-size:13px">{{ $med->reorder_level }}</td>
                            <td>
                                <span class="badge bg-warning">Low Stock</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">All medicines are sufficiently stocked</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><i class="fas fa-bolt me-2"></i>Quick Actions</div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('pharmacist.prescriptions.index') }}" class="btn btn-primary w-100 btn-sm">
                            <i class="fas fa-prescription me-1"></i>Prescriptions
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('pharmacist.medicines.index') }}" class="btn btn-success w-100 btn-sm">
                            <i class="fas fa-pills me-1"></i>Medicines
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('pharmacist.stock.index') }}" class="btn btn-warning w-100 btn-sm">
                            <i class="fas fa-boxes me-1"></i>Stock
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('pharmacist.medicines.create') }}" class="btn btn-info w-100 btn-sm">
                            <i class="fas fa-plus me-1"></i>Add Medicine
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pharmacy Stats --}}
        <div class="card mt-3">
            <div class="card-header"><i class="fas fa-chart-bar me-2"></i>Today's Summary</div>
            <div class="card-body" style="font-size:13px">
                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Prescriptions dispensed:</span> <strong>{{ $stats['dispensed_today'] }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Medicines low:</span> <strong class="text-warning">{{ $stats['low_stock'] }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Expiring ≤30 days:</span> <strong class="text-danger">{{ $stats['expiring_soon'] }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
