@extends('admin.layouts.app')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card border-0 shadow-sm rounded-4 style-glass-card mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.dashboard') }}" id="dashboardFilterForm">
                <div class="row align-items-end g-3">
                    <div class="col-md-4">
                        <label class="form-label text-uppercase text-dark fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 0.8px;">Store</label>
                        <select name="store_id" id="dashboardStoreSelect" class="form-select rounded-3 style-glass-input" style="height: 44px;" {{ (auth()->user()->store_id && !($canViewAllStores ?? (auth()->user()->hasRole('Admin') || auth()->user()->can('view stores')))) ? 'disabled' : '' }}>
                            <option value="">All Stores</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ (string)($selectedStoreId ?? request('store_id')) === (string)$store->id ? 'selected' : '' }}>{{ $store->store_name ?? $store->name }}</option>
                            @endforeach
                        </select>
                        @if(auth()->user()->store_id && !($canViewAllStores ?? (auth()->user()->hasRole('Admin') || auth()->user()->can('view stores'))))
                            <input type="hidden" name="store_id" value="{{ auth()->user()->store_id }}">
                        @endif
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-uppercase text-dark fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 0.8px;">Branch</label>
                        <select name="branch_id" id="dashboardBranchSelect" class="form-select rounded-3 style-glass-input" style="height: 44px;" {{ (auth()->user()->branch_id && !($canViewAllBranches ?? (auth()->user()->hasRole('Admin') || auth()->user()->can('view branches')))) ? 'disabled' : '' }}>
                            <option value="">All Branches</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" data-store-id="{{ $branch->store_id }}" {{ (string)($selectedBranchId ?? request('branch_id')) === (string)$branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        @if(auth()->user()->branch_id && !($canViewAllBranches ?? (auth()->user()->hasRole('Admin') || auth()->user()->can('view branches'))))
                            <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
                        @endif
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1 rounded-3 fw-bold text-white text-uppercase d-flex align-items-center justify-content-center" style="height: 44px; letter-spacing: 1px;">
                            <i class="bx bx-filter-alt me-2 fs-5"></i> Filter
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary flex-grow-1 rounded-3 fw-bold text-uppercase d-flex align-items-center justify-content-center" style="height: 44px; letter-spacing: 1px;">
                            <i class="bx bx-reset me-2 fs-5"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 style-glass-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="d-block text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Today Revenue</span>
                            <h4 class="mb-0 fw-bold text-dark">₹{{ number_format($todayRevenue, 2) }}</h4>
                        </div>
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(197, 160, 89, 0.18); border: 1px solid rgba(197, 160, 89, 0.3);">
                            <i class="bx bx-wallet fs-4" style="color: #9e7d3b;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 style-glass-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="d-block text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Monthly Revenue</span>
                            <h4 class="mb-0 fw-bold text-dark">₹{{ number_format($monthlyRevenue, 2) }}</h4>
                        </div>
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(78, 81, 82, 0.15); border: 1px solid rgba(78, 81, 82, 0.25);">
                            <i class="bx bx-line-chart fs-4" style="color: #4e5152;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 style-glass-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="d-block text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Total Customers</span>
                            <h4 class="mb-0 fw-bold text-dark">{{ $totalCustomers }}</h4>
                        </div>
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.25);">
                            <i class="bx bx-group fs-4" style="color: #10b981;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 style-glass-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="d-block text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">New Customers</span>
                            <h4 class="mb-0 fw-bold text-dark">{{ $newCustomers }}</h4>
                        </div>
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(229, 169, 60, 0.18); border: 1px solid rgba(229, 169, 60, 0.3);">
                            <i class="bx bx-user-plus fs-4" style="color: #d97706;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm rounded-4 h-100 style-glass-card">
                <div class="card-body p-3">
                    <span class="d-block text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Today Appts</span>
                    <h5 class="mb-0 fw-bold text-dark">{{ $todayAppointments }}</h5>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm rounded-4 h-100 style-glass-card">
                <div class="card-body p-3">
                    <span class="d-block text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Completed</span>
                    <h5 class="mb-0 fw-bold" style="color: #10b981;">{{ $completedAppointments }}</h5>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm rounded-4 h-100 style-glass-card">
                <div class="card-body p-3">
                    <span class="d-block text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Pending</span>
                    <h5 class="mb-0 fw-bold" style="color: #f59e0b;">{{ $pendingAppointments }}</h5>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm rounded-4 h-100 style-glass-card">
                <div class="card-body p-3">
                    <span class="d-block text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Staff Count</span>
                    <h5 class="mb-0 fw-bold text-dark">{{ $staffCount }}</h5>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm rounded-4 h-100 style-glass-card">
                <div class="card-body p-3">
                    <span class="d-block text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Total Expense</span>
                    <h5 class="mb-0 fw-bold" style="color: #ef4444;">₹{{ number_format($totalExpense, 2) }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 style-glass-card">
                <div class="card-header bg-transparent border-0 pt-3 px-3 pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Daily Revenue</h6>
                    <span class="badge rounded-pill bg-label-primary" style="font-size: 0.65rem;">Last 7 Days</span>
                </div>
                <div class="card-body p-3">
                    <div id="dailyRevenueChart" style="min-height: 250px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 style-glass-card">
                <div class="card-header bg-transparent border-0 pt-3 px-3 pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Monthly Revenue</h6>
                    <span class="badge rounded-pill bg-label-info" style="font-size: 0.65rem;">Monthly Overview</span>
                </div>
                <div class="card-body p-3">
                    <div id="monthlyRevenueChart" style="min-height: 250px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 style-glass-card">
                <div class="card-header bg-transparent border-0 pt-3 px-3 pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Appointment Trend</h6>
                    <span class="badge rounded-pill bg-label-warning" style="font-size: 0.65rem;">Volume Trend</span>
                </div>
                <div class="card-body p-3">
                    <div id="appointmentTrendChart" style="min-height: 250px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 style-glass-card">
                <div class="card-header bg-transparent border-0 pt-3 px-3 pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Customer Growth</h6>
                    <span class="badge rounded-pill bg-label-success" style="font-size: 0.65rem;">New Acquisition</span>
                </div>
                <div class="card-body p-3">
                    <div id="customerGrowthChart" style="min-height: 250px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 style-glass-card">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-3 px-3 pb-0">
                    <h6 class="card-title mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Recent Appointments</h6>
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-sm rounded-pill px-3 fw-bold text-white" style="background: linear-gradient(135deg, #4e5152 0%, #c5a059 100%); border: none; font-size: 0.75rem;">View All</a>
                </div>
                <div class="table-responsive text-nowrap px-3 pb-3 mt-3">
                    <table class="table table-borderless table-hover mb-0" style="font-size: 0.85rem;">
                        <thead class="border-bottom">
                            <tr>
                                <th class="text-uppercase text-muted fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px; padding-left: 0;">Customer</th>
                                <th class="text-uppercase text-muted fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Service</th>
                                <th class="text-uppercase text-muted fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Staff</th>
                                <th class="text-uppercase text-muted fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Date</th>
                                <th class="text-uppercase text-muted fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Time</th>
                                <th class="text-uppercase text-muted fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px; padding-right: 0;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAppointments as $appt)
                            <tr>
                                <td style="padding-left: 0;" class="fw-semibold text-dark">{{ $appt->customer->full_name ?? $appt->customer->name ?? 'N/A' }}</td>
                                <td>{{ $appt->service->service_name ?? 'N/A' }}</td>
                                <td>{{ $appt->staff->full_name ?? $appt->staff->name ?? 'N/A' }}</td>
                                <td>{{ $appt->appointment_date ? \Carbon\Carbon::parse($appt->appointment_date)->format('d M Y') : 'N/A' }}</td>
                                <td>{{ $appt->appointment_date ? \Carbon\Carbon::parse($appt->appointment_date)->format('h:i A') : 'N/A' }}</td>
                                <td style="padding-right: 0;">
                                    @php
                                        $statusClass = match($appt->status) {
                                            'completed' => 'bg-label-success',
                                            'confirmed' => 'bg-label-info',
                                            'checked_in' => 'bg-label-primary',
                                            'in_progress' => 'bg-label-secondary',
                                            'pending' => 'bg-label-warning',
                                            'cancelled' => 'bg-label-danger',
                                            'no_show' => 'bg-label-dark',
                                            default => 'bg-label-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }} rounded-pill" style="font-size: 0.7rem; padding: 0.35em 0.8em;">{{ ucfirst(str_replace('_', ' ', $appt->status)) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No recent appointments</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Slate & Warm Gold theme chart colors
    new ApexCharts(document.querySelector("#dailyRevenueChart"), {
        series: [{ name: 'Revenue', data: @json($dailyRevenueTotals ?? []) }],
        chart: { type: 'bar', height: 300, toolbar: { show: false } },
        colors: ['#4e5152'],
        plotOptions: { bar: { borderRadius: 8, columnWidth: '45%' } },
        xaxis: { categories: @json($dailyRevenueDates ?? []), labels: { style: { colors: '#787169', fontWeight: 600 } } },
        yaxis: { labels: { style: { colors: '#787169', fontWeight: 600 } } },
        grid: { borderColor: 'rgba(197, 160, 89, 0.15)' },
        tooltip: { theme: 'dark' }
    }).render();

    new ApexCharts(document.querySelector("#monthlyRevenueChart"), {
        series: [{ name: 'Revenue', data: @json($monthlyRevenueTotals ?? []) }],
        chart: { type: 'bar', height: 300, toolbar: { show: false } },
        colors: ['#c5a059'],
        plotOptions: { bar: { borderRadius: 8, columnWidth: '45%' } },
        xaxis: { categories: @json($monthlyRevenueLabels ?? []), labels: { style: { colors: '#787169', fontWeight: 600 } } },
        yaxis: { labels: { style: { colors: '#787169', fontWeight: 600 } } },
        grid: { borderColor: 'rgba(197, 160, 89, 0.15)' },
        tooltip: { theme: 'dark' }
    }).render();

    new ApexCharts(document.querySelector("#appointmentTrendChart"), {
        series: [{ name: 'Appointments', data: @json($appointmentTrendTotals ?? []) }],
        chart: { type: 'line', height: 300, toolbar: { show: false } },
        stroke: { curve: 'smooth', width: 3 },
        colors: ['#9e7d3b'],
        xaxis: { categories: @json($appointmentTrendDates ?? []), labels: { style: { colors: '#787169', fontWeight: 600 } } },
        yaxis: { labels: { style: { colors: '#787169', fontWeight: 600 } } },
        grid: { borderColor: 'rgba(197, 160, 89, 0.15)' },
        tooltip: { theme: 'dark' }
    }).render();

    new ApexCharts(document.querySelector("#customerGrowthChart"), {
        series: [{ name: 'Customers', data: @json($customerGrowthTotals ?? []) }],
        chart: { type: 'area', height: 300, toolbar: { show: false } },
        colors: ['#4e5152'],
        fill: { type: 'gradient', gradient: { opacityFrom: 0.45, opacityTo: 0.05 } },
        stroke: { width: 3 },
        xaxis: { categories: @json($customerGrowthDates ?? []), labels: { style: { colors: '#787169', fontWeight: 600 } } },
        yaxis: { labels: { style: { colors: '#787169', fontWeight: 600 } } },
        grid: { borderColor: 'rgba(197, 160, 89, 0.15)' },
        tooltip: { theme: 'dark' }
    }).render();
    if (window.setupDependentBranchSelect) {
        window.setupDependentBranchSelect('#dashboardStoreSelect', '#dashboardBranchSelect');
    }
});
</script>
@endsection
