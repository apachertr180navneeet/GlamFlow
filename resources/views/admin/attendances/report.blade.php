@extends('admin.layouts.app')

@section('style')
<style>
    /* Attendance Report Table Container & Horizontal Scroll */
    .attendance-table-container {
        position: relative;
        overflow-x: auto !important;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch !important;
        touch-action: pan-x pan-y !important;
        max-height: 72vh;
        border-radius: 0 0 12px 12px;
        scrollbar-width: thin;
        scrollbar-color: #8e8a84 #f1f3f5;
    }
    .attendance-table-container::-webkit-scrollbar {
        height: 10px !important;
        width: 10px !important;
    }
    .attendance-table-container::-webkit-scrollbar-track {
        background: #f1f3f5 !important;
        border-radius: 6px !important;
    }
    .attendance-table-container::-webkit-scrollbar-thumb {
        background: #8e8a84 !important;
        border-radius: 6px !important;
        border: 2px solid #f1f3f5 !important;
    }
    .attendance-table-container::-webkit-scrollbar-thumb:hover {
        background: #57585b !important;
    }

    .attendance-report-table {
        min-width: 1420px;
        border-collapse: separate !important;
        border-spacing: 0 !important;
    }
    .attendance-report-table th, 
    .attendance-report-table td {
        vertical-align: middle;
        padding: 0.35rem 0.25rem !important;
        font-size: 0.78rem;
    }

    /* Sticky First Column (#) */
    .attendance-report-table .sticky-col-index {
        position: sticky !important;
        left: 0 !important;
        z-index: 5 !important;
        background-color: #ffffff !important;
        width: 44px !important;
        min-width: 44px !important;
        max-width: 44px !important;
        text-align: center;
        border-right: 1px solid #e9ecef !important;
    }

    /* Sticky Second Column (Staff Member) */
    .attendance-report-table .sticky-col-staff {
        position: sticky !important;
        left: 44px !important;
        z-index: 5 !important;
        background-color: #ffffff !important;
        min-width: 200px !important;
        width: 210px !important;
        max-width: 240px !important;
        border-right: 2px solid #d0d4d9 !important;
        box-shadow: 4px 0 8px -2px rgba(0, 0, 0, 0.08) !important;
    }

    /* Sticky Header Row */
    .attendance-report-table thead th {
        position: sticky !important;
        top: 0 !important;
        z-index: 6 !important;
        background-color: #f8f9fa !important;
    }
    .attendance-report-table thead .sticky-col-index,
    .attendance-report-table thead .sticky-col-staff {
        z-index: 10 !important;
        background-color: #eef1f5 !important;
    }

    /* Hover States with sticky preservation */
    .attendance-report-table tbody tr:hover td {
        background-color: #f6f8fb !important;
    }
    .attendance-report-table tbody tr:hover td.sticky-col-index,
    .attendance-report-table tbody tr:hover td.sticky-col-staff {
        background-color: #edf2f7 !important;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 6px;
        font-size: 0.72rem;
        font-weight: 700;
        cursor: default;
        transition: transform 0.15s ease;
    }
    .status-badge:hover {
        transform: scale(1.15);
    }
    .badge-present { background-color: #d1e7dd; color: #0f5132; }
    .badge-absent { background-color: #f8d7da; color: #842029; }
    .badge-late { background-color: #fff3cd; color: #664d03; }
    .badge-half-day { background-color: #e0cffc; color: #3d0a91; }
    .badge-leave { background-color: #cff4fc; color: #055160; }
    .badge-holiday { background-color: #e2e3e5; color: #41464b; }
    .badge-empty { color: #adb5bd; font-weight: normal; font-size: 0.85rem; }
    .weekend-header { background-color: #fff2f2 !important; color: #dc3545 !important; }
    .weekend-cell { background-color: #fcf8f8; }
    
    @media print {
        body { background: #fff !important; font-size: 11px !important; }
        .layout-menu, .layout-navbar, .content-footer, .no-print, .btn, .card-header form, .scroll-hint-bar { display: none !important; }
        .layout-page { padding: 0 !important; margin: 0 !important; }
        .content-wrapper { padding: 0 !important; }
        .container-fluid { padding: 0 !important; max-width: 100% !important; }
        .card { border: none !important; box-shadow: none !important; }
        .table-responsive, .attendance-table-container { overflow: visible !important; max-height: none !important; }
        .attendance-report-table { min-width: 100% !important; }
        .attendance-report-table .sticky-col-index,
        .attendance-report-table .sticky-col-staff,
        .attendance-report-table thead th { position: static !important; box-shadow: none !important; }
        .status-badge { width: 20px; height: 20px; font-size: 0.65rem; }
        .card-header { border-bottom: 2px solid #333 !important; }
    }
</style>
@endsection

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <!-- Header with breadcrumb and actions -->
    @php
        $currentCarbon = \Carbon\Carbon::createFromDate($year, $month, 1);
        $prevMonthDate = (clone $currentCarbon)->subMonth();
        $nextMonthDate = (clone $currentCarbon)->addMonth();
        $isCurrentMonth = ((int)$month === (int)now()->month && (int)$year === (int)now()->year);
        
        $queryParams = request()->except(['month', 'year']);
        $prevUrl = route('admin.attendances.report', array_merge($queryParams, ['month' => $prevMonthDate->month, 'year' => $prevMonthDate->year]));
        $nextUrl = route('admin.attendances.report', array_merge($queryParams, ['month' => $nextMonthDate->month, 'year' => $nextMonthDate->year]));
        $currentMonthUrl = route('admin.attendances.report', array_merge($queryParams, ['month' => now()->month, 'year' => now()->year]));
    @endphp

    <!-- Header with breadcrumb and actions -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <h4 class="fw-bold mb-0"><i class="bx bx-calendar-check text-primary me-2"></i>Attendance Report</h4>
                @if($isCurrentMonth)
                    <span class="badge bg-label-success"><i class="bx bx-check-circle me-1"></i>Current Month</span>
                @else
                    <a href="{{ $currentMonthUrl }}" class="badge bg-label-primary text-decoration-none" title="Click to view current month">
                        <i class="bx bx-calendar me-1"></i> Switch to Current Month
                    </a>
                @endif
            </div>
            <small class="text-muted">Monthly attendance overview for <strong>{{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</strong></small>
        </div>
        <div class="d-flex align-items-center gap-2 no-print">
            <div class="btn-group btn-group-sm me-1" role="group">
                <a href="{{ $prevUrl }}" class="btn btn-outline-secondary" title="Previous Month: {{ $prevMonthDate->format('F Y') }}">
                    <i class="bx bx-chevron-left me-1"></i>{{ $prevMonthDate->format('M') }}
                </a>
                <a href="{{ $currentMonthUrl }}" class="btn {{ $isCurrentMonth ? 'btn-primary' : 'btn-outline-primary' }}" title="Current Month ({{ now()->format('F Y') }})">
                    <i class="bx bx-calendar-event me-1"></i> This Month
                </a>
                <a href="{{ $nextUrl }}" class="btn btn-outline-secondary" title="Next Month: {{ $nextMonthDate->format('F Y') }}">
                    {{ $nextMonthDate->format('M') }}<i class="bx bx-chevron-right ms-1"></i>
                </a>
            </div>
            <a href="{{ route('admin.attendances.index', ['month' => $month, 'year' => $year]) }}" class="btn btn-sm btn-outline-primary">
                <i class="bx bx-calendar-edit me-1"></i> Daily Sheet
            </a>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                <i class="bx bx-printer me-1"></i> Print
            </button>
            <button type="button" class="btn btn-sm btn-outline-success" id="exportCsvBtn">
                <i class="bx bx-download me-1"></i> Export CSV
            </button>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card shadow-sm border-0 mb-4 no-print" style="border-radius: 12px;">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.attendances.report') }}" class="row g-2 align-items-end" id="filterForm">
                <div class="col-6 col-sm-4 col-md-2">
                    <label class="form-label small text-muted fw-semibold">Month</label>
                    <select name="month" id="reportMonthSelect" class="form-select form-select-sm auto-submit-filter">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ (int)$month === (int)$m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }} {{ (int)$m === (int)now()->month ? '★ (Current)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-sm-4 col-md-2">
                    <label class="form-label small text-muted fw-semibold">Year</label>
                    <select name="year" id="reportYearSelect" class="form-select form-select-sm auto-submit-filter">
                        @foreach(range(date('Y') + 1, date('Y') - 5) as $y)
                            <option value="{{ $y }}" {{ (int)$year === (int)$y ? 'selected' : '' }}>{{ $y }} {{ (int)$y === (int)now()->year ? '(Current)' : '' }}</option>
                        @endforeach
                    </select>
                </div>

                @if(isset($stores) && count($stores) > 0 && (auth()->user()->hasRole('Admin') || !auth()->user()->store_id))
                <div class="col-12 col-sm-4 col-md-3">
                    <label class="form-label small text-muted fw-semibold">Store</label>
                    <select name="store_id" id="reportStoreSelect" class="form-select form-select-sm auto-submit-filter">
                        <option value="">All Stores</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ (string)$selectedStoreId === (string)$store->id ? 'selected' : '' }}>
                                {{ $store->store_name ?? $store->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                @if(isset($branches) && count($branches) > 0)
                <div class="col-12 col-sm-4 col-md-3">
                    <label class="form-label small text-muted fw-semibold">Branch</label>
                    <select name="branch_id" id="reportBranchSelect" class="form-select form-select-sm auto-submit-filter">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" data-store-id="{{ $branch->store_id }}" data-store="{{ $branch->store_id }}" {{ (string)$selectedBranchId === (string)$branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="col-12 col-sm-4 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary flex-grow-1"><i class="bx bx-filter-alt me-1"></i>Filter</button>
                    <a href="{{ route('admin.attendances.report') }}" class="btn btn-sm btn-outline-secondary" title="Reset to Current Month"><i class="bx bx-reset"></i></a>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    @php
        $totalStaffCount = $staffs->count();
        $totalPresentAll = 0;
        $totalAbsentAll = 0;
        $totalLateAll = 0;
        $totalHalfDayAll = 0;
        $totalLeaveAll = 0;
        $totalHolidayAll = 0;

        foreach($staffs as $s) {
            $totalPresentAll += $s->attendances->where('status', 'present')->count();
            $totalAbsentAll += $s->attendances->where('status', 'absent')->count();
            $totalLateAll += $s->attendances->where('status', 'late')->count();
            $totalHalfDayAll += $s->attendances->where('status', 'half_day')->count();
            $totalLeaveAll += $s->attendances->where('status', 'leave')->count();
            $totalHolidayAll += $s->attendances->where('status', 'holiday')->count();
        }
    @endphp
    <div class="row g-3 mb-4 no-print">
        <div class="col-6 col-sm-4 col-lg-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-3 text-center">
                    <span class="avatar-initial rounded bg-label-primary p-2 mb-2 d-inline-block"><i class="bx bx-user fs-4"></i></span>
                    <h5 class="card-title mb-0 fw-bold">{{ $totalStaffCount }}</h5>
                    <small class="text-muted">Total Staff</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-lg-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-3 text-center">
                    <span class="avatar-initial rounded bg-label-success p-2 mb-2 d-inline-block"><i class="bx bx-check-double fs-4"></i></span>
                    <h5 class="card-title mb-0 fw-bold text-success">{{ $totalPresentAll }}</h5>
                    <small class="text-muted">Present (Days)</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-lg-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-3 text-center">
                    <span class="avatar-initial rounded bg-label-danger p-2 mb-2 d-inline-block"><i class="bx bx-x fs-4"></i></span>
                    <h5 class="card-title mb-0 fw-bold text-danger">{{ $totalAbsentAll }}</h5>
                    <small class="text-muted">Absent (Days)</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-lg-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-3 text-center">
                    <span class="avatar-initial rounded bg-label-warning p-2 mb-2 d-inline-block"><i class="bx bx-time-five fs-4"></i></span>
                    <h5 class="card-title mb-0 fw-bold text-warning">{{ $totalLateAll }}</h5>
                    <small class="text-muted">Late Marks</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-lg-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-3 text-center">
                    <span class="avatar-initial rounded bg-label-info p-2 mb-2 d-inline-block"><i class="bx bx-pie-chart-alt fs-4"></i></span>
                    <h5 class="card-title mb-0 fw-bold text-info">{{ $totalHalfDayAll }}</h5>
                    <small class="text-muted">Half Days</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-lg-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-3 text-center">
                    <span class="avatar-initial rounded bg-label-secondary p-2 mb-2 d-inline-block"><i class="bx bx-calendar-star fs-4"></i></span>
                    <h5 class="card-title mb-0 fw-bold text-secondary">{{ $totalLeaveAll + $totalHolidayAll }}</h5>
                    <small class="text-muted">Leave / Holiday</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Grid Card -->
    <div class="card shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center flex-wrap gap-2 py-3 border-bottom">
            <div>
                <h5 class="card-title mb-0 fw-bold text-dark">
                    <i class="bx bx-table me-2 text-primary"></i>Attendance Sheet - {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}
                </h5>
            </div>
            <!-- Legend -->
            <div class="d-flex align-items-center flex-wrap gap-2 small">
                <span class="badge badge-present px-2 py-1">P = Present</span>
                <span class="badge badge-absent px-2 py-1">A = Absent</span>
                <span class="badge badge-half-day px-2 py-1">HD = Half Day</span>
                <span class="badge badge-late px-2 py-1">L = Late</span>
                <span class="badge badge-leave px-2 py-1">LV = Leave</span>
                <span class="badge badge-holiday px-2 py-1">H = Holiday</span>
                <span class="text-muted ms-1"><span class="badge badge-empty">-</span> = No Record</span>
            </div>
        </div>

        <!-- Horizontal Scroll Hint Bar for Tablet/Mobile -->
        <div class="scroll-hint-bar d-flex align-items-center justify-content-between px-3 py-2 bg-light border-bottom text-muted small">
            <span class="d-flex align-items-center gap-1">
                <i class="bx bx-info-circle text-primary fs-6"></i>
                <span class="d-none d-md-inline">Staff list is pinned. Scroll horizontally to view all days and metrics.</span>
                <span class="d-inline d-md-none">Swipe horizontally to view full month.</span>
            </span>
            <span class="badge bg-label-secondary d-inline-flex align-items-center gap-1">
                <i class="bx bx-left-arrow-alt"></i> Scroll Table <i class="bx bx-right-arrow-alt"></i>
            </span>
        </div>

        <div class="table-responsive text-nowrap attendance-table-container">
            <table class="table table-bordered table-hover attendance-report-table mb-0" id="attendanceReportTable">
                <thead class="table-light sticky-top-header">
                    <tr>
                        <th class="fw-bold text-center sticky-col-index" style="width: 44px; min-width: 44px; max-width: 44px;">#</th>
                        <th class="fw-bold sticky-col-staff" style="min-width: 200px; width: 210px;">Staff Member</th>
                        @foreach($dates as $dateItem)
                            <th class="text-center p-1 {{ $dateItem['is_weekend'] ? 'weekend-header' : '' }}" style="min-width: 32px; font-size: 0.72rem;">
                                <div class="fw-bold">{{ $dateItem['day'] }}</div>
                                <div style="font-size: 0.65rem; opacity: 0.85;">{{ $dateItem['day_name'] }}</div>
                            </th>
                        @endforeach
                        <th class="text-center fw-bold bg-light text-success" title="Present Days" style="min-width: 40px; font-size: 0.75rem;">P</th>
                        <th class="text-center fw-bold bg-light text-danger" title="Absent Days" style="min-width: 40px; font-size: 0.75rem;">A</th>
                        <th class="text-center fw-bold bg-light text-info" title="Half Days" style="min-width: 40px; font-size: 0.75rem;">HD</th>
                        <th class="text-center fw-bold bg-light text-warning" title="Late" style="min-width: 40px; font-size: 0.75rem;">L</th>
                        <th class="text-center fw-bold bg-light text-primary" title="Leave" style="min-width: 40px; font-size: 0.75rem;">LV</th>
                        <th class="text-center fw-bold bg-light text-secondary" title="Holiday" style="min-width: 40px; font-size: 0.75rem;">H</th>
                        <th class="text-center fw-bold bg-light" title="Total Present / Total Days" style="min-width: 60px; font-size: 0.75rem;">Total</th>
                        <th class="text-center fw-bold bg-light" title="Attendance Percentage" style="min-width: 60px; font-size: 0.75rem;">%</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staffs as $index => $staff)
                        @php
                            // Index attendances by Y-m-d string for fast O(1) matching
                            $attendanceMap = $staff->attendances->keyBy(function($item) {
                                if ($item->date instanceof \Carbon\Carbon || $item->date instanceof \DateTimeInterface) {
                                    return $item->date->format('Y-m-d');
                                }
                                return substr((string)$item->date, 0, 10);
                            });

                            $presentCount = $staff->attendances->where('status', 'present')->count();
                            $absentCount = $staff->attendances->where('status', 'absent')->count();
                            $halfDayCount = $staff->attendances->where('status', 'half_day')->count();
                            $lateCount = $staff->attendances->where('status', 'late')->count();
                            $leaveCount = $staff->attendances->where('status', 'leave')->count();
                            $holidayCount = $staff->attendances->where('status', 'holiday')->count();

                            // Calculate effective attended days (Present + Late + 0.5 * HalfDay)
                            $effectiveAttended = $presentCount + $lateCount + ($halfDayCount * 0.5);
                            $attendancePercent = $daysInMonth > 0 ? round(($effectiveAttended / $daysInMonth) * 100, 1) : 0;
                        @endphp
                        <tr>
                            <td class="text-center text-muted small sticky-col-index">{{ $index + 1 }}</td>
                            <td class="sticky-col-staff">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xs me-2" style="width: 28px; height: 28px;">
                                        <span class="avatar-initial rounded-circle bg-label-primary" style="font-size: 0.75rem; font-weight: 600;">
                                            {{ strtoupper(substr($staff->first_name ?? $staff->full_name ?? 'S', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark" style="font-size: 0.82rem;">
                                            {{ $staff->full_name ?? ($staff->first_name . ' ' . $staff->last_name) }}
                                        </div>
                                        <small class="text-muted" style="font-size: 0.7rem;">
                                            {{ $staff->position ?? 'Staff' }}
                                            @if($staff->branch)
                                                &bull; {{ $staff->branch->name }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </td>

                            @foreach($dates as $dateItem)
                                @php
                                    $att = $attendanceMap->get($dateItem['date']);
                                    $status = $att ? $att->status : null;
                                    
                                    $badgeClass = match($status) {
                                        'present' => 'badge-present',
                                        'absent' => 'badge-absent',
                                        'late' => 'badge-late',
                                        'half_day' => 'badge-half-day',
                                        'leave' => 'badge-leave',
                                        'holiday' => 'badge-holiday',
                                        default => 'badge-empty'
                                    };

                                    $label = match($status) {
                                        'present' => 'P',
                                        'absent' => 'A',
                                        'late' => 'L',
                                        'half_day' => 'HD',
                                        'leave' => 'LV',
                                        'holiday' => 'H',
                                        default => '-'
                                    };

                                    $tooltipTitle = '';
                                    if ($att) {
                                        $statusFormatted = ucfirst(str_replace('_', ' ', $att->status));
                                        $tooltipTitle = $statusFormatted;
                                        if ($att->check_in) {
                                            $checkInStr = is_string($att->check_in) ? $att->check_in : $att->check_in->format('H:i');
                                            $tooltipTitle .= " | In: {$checkInStr}";
                                        }
                                        if ($att->check_out) {
                                            $checkOutStr = is_string($att->check_out) ? $att->check_out : $att->check_out->format('H:i');
                                            $tooltipTitle .= " | Out: {$checkOutStr}";
                                        }
                                        if ($att->remarks) {
                                            $tooltipTitle .= " ({$att->remarks})";
                                        }
                                    }
                                @endphp
                                <td class="text-center p-1 {{ $dateItem['is_weekend'] ? 'weekend-cell' : '' }}">
                                    <span class="status-badge {{ $badgeClass }}" 
                                          @if($tooltipTitle) data-bs-toggle="tooltip" data-bs-placement="top" data-bs-container="body" title="{{ $tooltipTitle }}" @endif>
                                        {{ $label }}
                                    </span>
                                </td>
                            @endforeach

                            <td class="text-center fw-bold text-success" style="font-size: 0.8rem;">{{ $presentCount }}</td>
                            <td class="text-center fw-bold text-danger" style="font-size: 0.8rem;">{{ $absentCount }}</td>
                            <td class="text-center fw-bold text-info" style="font-size: 0.8rem;">{{ $halfDayCount }}</td>
                            <td class="text-center fw-bold text-warning" style="font-size: 0.8rem;">{{ $lateCount }}</td>
                            <td class="text-center fw-bold text-primary" style="font-size: 0.8rem;">{{ $leaveCount }}</td>
                            <td class="text-center fw-bold text-secondary" style="font-size: 0.8rem;">{{ $holidayCount }}</td>
                            <td class="text-center fw-bold text-dark" style="font-size: 0.8rem;">
                                {{ $effectiveAttended }} <small class="text-muted">/ {{ $daysInMonth }}</small>
                            </td>
                            <td class="text-center">
                                @php
                                    $pctColor = match(true) {
                                        $attendancePercent >= 90 => 'bg-label-success',
                                        $attendancePercent >= 75 => 'bg-label-info',
                                        $attendancePercent >= 50 => 'bg-label-warning',
                                        default => 'bg-label-danger'
                                    };
                                @endphp
                                <span class="badge {{ $pctColor }}" style="font-size: 0.72rem;">{{ $attendancePercent }}%</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($dates) + 10 }}" class="text-center py-5 text-muted">
                                <div class="my-3">
                                    <i class="bx bx-user-x fs-1 text-secondary mb-2"></i>
                                    <p class="mb-0 fw-semibold">No active staff members found for the selected filter.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // Initialize tooltips with container: 'body' so they don't jump or cause scrollbars
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, { container: 'body' });
        });
    }

    // Auto-submit on month and year filter change
    $('#reportMonthSelect, #reportYearSelect').on('change', function() {
        $('#filterForm').submit();
    });

    // Dependent store-branch dropdowns
    if (window.setupDependentBranchSelect) {
        window.setupDependentBranchSelect('#reportStoreSelect', '#reportBranchSelect');
    }

    // Export Table to CSV
    $('#exportCsvBtn').on('click', function() {
        let table = document.getElementById('attendanceReportTable');
        let rows = table.querySelectorAll('tr');
        let csv = [];

        for (let i = 0; i < rows.length; i++) {
            let row = [], cols = rows[i].querySelectorAll('td, th');
            for (let j = 0; j < cols.length; j++) {
                let text = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, ' ').replace(/\s+/g, ' ').trim();
                text = text.replace(/"/g, '""');
                row.push('"' + text + '"');
            }
            csv.push(row.join(','));
        }

        let csvString = csv.join('\n');
        let filename = 'Attendance_Report_' + '{{ $month }}_{{ $year }}' + '.csv';
        let blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
        let link = document.createElement('a');
        let url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
});
</script>
@endsection
