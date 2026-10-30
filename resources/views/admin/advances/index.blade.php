@extends('admin.layouts.app')

@section('style')
<style>
    .table-responsive.has-open-dropdown,
    .card-body.has-open-dropdown,
    .card.has-open-dropdown,
    .row.has-open-dropdown,
    .modal-content.has-open-dropdown,
    .modal-body.has-open-dropdown {
        position: relative !important;
        z-index: 9999 !important;
        overflow: visible !important;
    }
    .custom-ajax-wrapper.has-open-dropdown {
        position: relative !important;
        z-index: 9999 !important;
    }
    .custom-ajax-wrapper { position: relative; }
    .custom-ajax-menu {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 99999 !important;
        background: #ffffff !important;
        border: 1px solid rgba(197, 160, 89, 0.4);
        border-radius: 12px;
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.25) !important;
        max-height: 230px;
        overflow-y: auto;
        display: none;
    }
    .custom-ajax-menu .dropdown-item {
        padding: 8px 14px;
        cursor: pointer;
        border-bottom: 1px solid #f8f9fa;
        white-space: normal;
        background: #ffffff !important;
    }
    .custom-ajax-menu .dropdown-item:hover {
        background-color: rgba(197, 160, 89, 0.15) !important;
        color: #9e7d3b;
    }
</style>
@endsection

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="bx bx-money me-2 text-primary"></i>Employee Advance Management</h4>
            <p class="text-muted mb-0">Record salary advances given to employees and track monthly payroll deductions</p>
        </div>
        @can('create advances')
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addAdvanceModal">
            <i class="bx bx-plus me-1"></i> Give New Advance
        </button>
        @endcan
    </div>

    <!-- Summary Metrics -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-start border-4 border-primary rounded-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted fw-semibold small text-uppercase">Total Advances Issued</span>
                            <h3 class="mb-0 mt-1 fw-bold text-primary">₹{{ number_format($totalAdvance, 2) }}</h3>
                        </div>
                        <div class="avatar avatar-md bg-label-primary rounded p-2 d-flex align-items-center justify-content-center">
                            <i class="bx bx-wallet fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-start border-4 border-warning rounded-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted fw-semibold small text-uppercase">Pending Payroll Deductions</span>
                            <h3 class="mb-0 mt-1 fw-bold text-warning">₹{{ number_format($totalPending, 2) }}</h3>
                        </div>
                        <div class="avatar avatar-md bg-label-warning rounded p-2 d-flex align-items-center justify-content-center">
                            <i class="bx bx-time-five fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-start border-4 border-success rounded-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted fw-semibold small text-uppercase">Deducted in Payroll</span>
                            <h3 class="mb-0 mt-1 fw-bold text-success">₹{{ number_format($totalDeducted, 2) }}</h3>
                        </div>
                        <div class="avatar avatar-md bg-label-success rounded p-2 d-flex align-items-center justify-content-center">
                            <i class="bx bx-check-double fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.advances.index') }}" class="row g-3 align-items-end" id="advancesFilterForm">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Employee / Staff</label>
                    <div class="custom-ajax-wrapper" id="filterStaffPickerWrapper">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bx bx-user text-muted"></i></span>
                            @php
                                $selectedFilterStaff = request('staff_id') ? $staffs->firstWhere('id', request('staff_id')) : null;
                                $filterStaffText = $selectedFilterStaff ? ($selectedFilterStaff->full_name . ($selectedFilterStaff->position ? ' (' . $selectedFilterStaff->position . ')' : '')) : '';
                            @endphp
                            <input type="text" class="form-control" id="filter_staff_search_input" placeholder="Type name to search employee..." value="{{ $filterStaffText }}" autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" id="clear_filter_staff_btn" style="display: {{ request('staff_id') ? 'inline-block' : 'none' }};"><i class="bx bx-x"></i></button>
                        </div>
                        <input type="hidden" name="staff_id" id="filter_staff_id" value="{{ request('staff_id') }}">
                        <div class="custom-ajax-menu" id="filter_staff_results_menu"></div>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Deduction Month</label>
                    <select name="deduction_month" class="form-select">
                        <option value="">All Months</option>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ request('deduction_month') == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Deduction Year</label>
                    <select name="deduction_year" class="form-select">
                        <option value="">All Years</option>
                        @for($y = date('Y') - 1; $y <= date('Y') + 2; $y++)
                            <option value="{{ $y }}" {{ request('deduction_year', date('Y')) == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="deducted" {{ request('status') == 'deducted' ? 'selected' : '' }}>Deducted</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                @if(($canViewAllStores ?? (Auth::user()->hasRole('Admin') || Auth::user()->can('view stores'))) && isset($stores) && count($stores) > 0)
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Store</label>
                    <select name="store_id" id="advancesStoreFilter" class="form-select">
                        <option value="">All Stores</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ (string)($selectedStoreId ?? request('store_id')) === (string)$store->id ? 'selected' : '' }}>
                                {{ $store->store_name ?? $store->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-50"><i class="bx bx-filter-alt me-1"></i> Filter</button>
                    <a href="{{ route('admin.advances.index') }}" class="btn btn-outline-secondary w-50"><i class="bx bx-reset me-1"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Advances Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">S. No.</th>
                            <th>Employee</th>
                            <th>Advance Amount</th>
                            <th>Advance Date</th>
                            <th>Deduction Month</th>
                            <th>Reason / Notes</th>
                            <th>Status</th>
                            @if(auth()->user()->can('edit advances') || auth()->user()->can('delete advances'))
                            <th class="text-end pe-3">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($advances as $index => $advance)
                        <tr>
                            <td class="ps-3 fw-semibold text-muted">{{ $advances->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2 bg-label-info rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bx bx-user"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-dark fw-bold">{{ $advance->staff->full_name ?? 'N/A' }}</h6>
                                        <small class="text-muted">{{ $advance->staff->position ?? 'Staff' }} @if($advance->store) • {{ $advance->store->store_name ?? $advance->store->name }} @endif</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold fs-6 text-primary">₹{{ number_format($advance->amount, 2) }}</span>
                            </td>
                            <td>
                                <span class="text-dark fw-medium">{{ \Carbon\Carbon::parse($advance->advance_date)->format('d M, Y') }}</span>
                            </td>
                            <td>
                                <span class="badge bg-label-secondary px-3 py-2 fs-7 fw-semibold">
                                    <i class="bx bx-calendar me-1"></i>{{ date('F', mktime(0, 0, 0, $advance->deduction_month, 1)) }} {{ $advance->deduction_year }}
                                </span>
                            </td>
                            <td>
                                <span class="text-wrap text-muted small" style="max-width: 220px; display: inline-block;">
                                    {{ $advance->reason ?: 'Salary Advance' }}
                                </span>
                            </td>
                            <td>
                                @if($advance->status == 'pending')
                                    <span class="badge bg-label-warning px-3 py-2"><i class="bx bx-time me-1"></i> Pending Deduction</span>
                                @elseif($advance->status == 'deducted')
                                    <span class="badge bg-label-success px-3 py-2"><i class="bx bx-check-circle me-1"></i> Deducted</span>
                                @else
                                    <span class="badge bg-label-secondary px-3 py-2"><i class="bx bx-x-circle me-1"></i> Cancelled</span>
                                @endif
                            </td>
                            @if(auth()->user()->can('edit advances') || auth()->user()->can('delete advances'))
                            <td class="text-end pe-3">
                                <div class="dropdown">
                                    <button class="btn p-0 dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded fs-5 text-muted"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @can('edit advances')
                                        <li>
                                            <a class="dropdown-item edit-advance" href="javascript:void(0);"
                                               data-id="{{ $advance->id }}"
                                               data-staff_id="{{ $advance->staff_id }}"
                                               data-amount="{{ $advance->amount }}"
                                               data-advance_date="{{ \Carbon\Carbon::parse($advance->advance_date)->format('Y-m-d') }}"
                                               data-deduction_month="{{ $advance->deduction_month }}"
                                               data-deduction_year="{{ $advance->deduction_year }}"
                                               data-status="{{ $advance->status }}"
                                               data-reason="{{ $advance->reason }}">
                                                <i class="bx bx-edit-alt me-1 text-primary"></i> Edit
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        @if($advance->status != 'deducted')
                                            <li>
                                                <a class="dropdown-item update-status" href="javascript:void(0);" data-id="{{ $advance->id }}" data-status="deducted">
                                                    <i class="bx bx-check-circle me-1 text-success"></i> Mark as Deducted
                                                </a>
                                            </li>
                                        @endif
                                        @if($advance->status != 'pending')
                                            <li>
                                                <a class="dropdown-item update-status" href="javascript:void(0);" data-id="{{ $advance->id }}" data-status="pending">
                                                    <i class="bx bx-time me-1 text-warning"></i> Mark as Pending
                                                </a>
                                            </li>
                                        @endif
                                        @if($advance->status != 'cancelled')
                                            <li>
                                                <a class="dropdown-item update-status" href="javascript:void(0);" data-id="{{ $advance->id }}" data-status="cancelled">
                                                    <i class="bx bx-x-circle me-1 text-secondary"></i> Cancel Advance
                                                </a>
                                            </li>
                                        @endif
                                        @endcan
                                        @can('delete advances')
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger delete-advance" href="javascript:void(0);" data-id="{{ $advance->id }}">
                                                <i class="bx bx-trash me-1"></i> Delete
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bx bx-money display-4 mb-2 d-block opacity-25"></i>
                                No employee advances found. Click <strong>"Give New Advance"</strong> to issue a salary advance.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($advances->hasPages())
                <div class="card-footer bg-transparent border-0 py-3">
                    {{ $advances->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Advance Modal -->
<div class="modal fade" id="addAdvanceModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white"><i class="bx bx-plus-circle me-1"></i> Issue Employee Salary Advance</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addAdvanceForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Employee / Staff <span class="text-danger">*</span></label>
                        <div class="custom-ajax-wrapper" id="addStaffPickerWrapper">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bx bx-user text-muted"></i></span>
                                <input type="text" class="form-control" id="add_staff_search_input" placeholder="Type name or phone to search employee..." autocomplete="off" required>
                                <button type="button" class="btn btn-outline-secondary" id="clear_add_staff_btn" style="display: none;"><i class="bx bx-x"></i></button>
                            </div>
                            <input type="hidden" name="staff_id" id="add_staff_id" required>
                            <div class="custom-ajax-menu" id="add_staff_results_menu"></div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Advance Amount (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="1" class="form-control" name="amount" placeholder="e.g. 5000" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Advance Given Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="advance_date" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Deduction Month <span class="text-danger">*</span></label>
                            <select class="form-select" name="deduction_month" required>
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Deduction Year <span class="text-danger">*</span></label>
                            <select class="form-select" name="deduction_year" required>
                                @for($y = date('Y'); $y <= date('Y') + 2; $y++)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Reason / Description</label>
                        <textarea class="form-control" name="reason" rows="3" placeholder="Specify reason or notes for issuing this advance..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveAdvanceBtn"><i class="bx bx-check me-1"></i> Save Advance</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Advance Modal -->
<div class="modal fade" id="editAdvanceModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title text-white"><i class="bx bx-edit me-1"></i> Edit Employee Advance</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editAdvanceForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_advance_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Employee / Staff <span class="text-danger">*</span></label>
                        <div class="custom-ajax-wrapper" id="editStaffPickerWrapper">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bx bx-user text-muted"></i></span>
                                <input type="text" class="form-control" id="edit_staff_search_input" placeholder="Type name or phone to search employee..." autocomplete="off" required>
                                <button type="button" class="btn btn-outline-secondary" id="clear_edit_staff_btn" style="display: none;"><i class="bx bx-x"></i></button>
                            </div>
                            <input type="hidden" name="staff_id" id="edit_staff_id" required>
                            <div class="custom-ajax-menu" id="edit_staff_results_menu"></div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Advance Amount (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="1" class="form-control" name="amount" id="edit_amount" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Advance Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="advance_date" id="edit_advance_date" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Deduction Month <span class="text-danger">*</span></label>
                            <select class="form-select" name="deduction_month" id="edit_deduction_month" required>
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Deduction Year <span class="text-danger">*</span></label>
                            <select class="form-select" name="deduction_year" id="edit_deduction_year" required>
                                @for($y = date('Y') - 1; $y <= date('Y') + 2; $y++)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" id="edit_status" required>
                                <option value="pending">Pending</option>
                                <option value="deducted">Deducted</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Reason / Description</label>
                        <textarea class="form-control" name="reason" id="edit_reason" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="updateAdvanceBtn"><i class="bx bx-check me-1"></i> Update Advance</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        const staffsMap = {
            @foreach($staffs as $s)
                {{ $s->id }}: {
                    name: "{{ addslashes($s->full_name) }}",
                    position: "{{ addslashes($s->position ?? '') }}",
                    phone: "{{ addslashes($s->phone ?? '') }}",
                    store_id: "{{ $s->store_id }}"
                },
            @endforeach
        };

        function showCustomMenu(menu) {
            if (!menu || typeof menu.show !== 'function') return;
            $('.custom-ajax-menu').not(menu).hide();
            $('.has-open-dropdown').removeClass('has-open-dropdown');
            
            menu.show();
            menu.parents('.custom-ajax-wrapper, .table-responsive, .card-body, .card, .row, .modal-body, .modal-content').addClass('has-open-dropdown');
        }

        function hideCustomMenu() {
            $('.custom-ajax-menu').hide();
            $('.has-open-dropdown').removeClass('has-open-dropdown');
        }

        // Hide all menus when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.custom-ajax-wrapper').length) {
                hideCustomMenu();
            }
        });

        // Prevent click events inside dropdown menus from bubbling
        $(document).on('click', '.custom-ajax-menu', function(e) {
            e.stopPropagation();
        });

        function setupStaffPicker(inputId, hiddenId, menuId, clearBtnId, getStoreIdFn) {
            let searchTimer;

            function doSearch(q) {
                const menu = $(menuId);
                const storeId = typeof getStoreIdFn === 'function' ? getStoreIdFn() : null;

                $.ajax({
                    url: '{{ route("admin.advances.search-staffs") }}',
                    method: 'GET',
                    data: { q: q, store_id: storeId },
                    success: function(data) {
                        menu.empty();
                        const list = Array.isArray(data) ? data : (data.results || []);
                        if (list.length === 0) {
                            menu.append('<div class="p-3 text-muted text-center small">No employees found</div>');
                        } else {
                            data.forEach(function(st) {
                                const pos = st.position ? `<small class="text-muted d-block"><i class="bx bx-briefcase me-1"></i>${st.position}</small>` : '';
                                const ph = st.phone ? `<small class="text-muted d-block"><i class="bx bx-phone me-1"></i>${st.phone}</small>` : '';
                                menu.append(`
                                    <div class="dropdown-item select-staff-item" data-id="${st.id}" data-name="${st.name}" data-position="${st.position || ''}" data-phone="${st.phone || ''}">
                                        <div class="fw-bold">${st.name}</div>
                                        ${pos}
                                        ${ph}
                                    </div>
                                `);
                            });
                        }
                        showCustomMenu(menu);
                    }
                });
            }

            $(inputId).on('focus input', function(e) {
                e.stopPropagation();
                clearTimeout(searchTimer);
                const q = $(this).val();
                searchTimer = setTimeout(function() {
                    doSearch(q);
                }, 150);
            });

            $(menuId).on('click', '.select-staff-item', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const id = $(this).data('id');
                const name = $(this).data('name');
                const pos = $(this).data('position');
                const displayText = pos ? `${name} (${pos})` : name;

                $(hiddenId).val(id);
                $(inputId).val(displayText);
                $(clearBtnId).show();
                hideCustomMenu();
            });

            $(clearBtnId).on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(hiddenId).val('');
                $(inputId).val('');
                $(this).hide();
            });
        }

        // Initialize Employee search for Filter Card
        setupStaffPicker(
            '#filter_staff_search_input',
            '#filter_staff_id',
            '#filter_staff_results_menu',
            '#clear_filter_staff_btn',
            function() { return $('#advancesStoreFilter').val(); }
        );

        // When store filter changes, clear the filter employee if it doesn't match store
        $('#advancesStoreFilter').on('change', function() {
            const storeId = $(this).val();
            const currentStaffId = $('#filter_staff_id').val();
            if (storeId && currentStaffId && staffsMap[currentStaffId]) {
                if (String(staffsMap[currentStaffId].store_id) !== String(storeId)) {
                    $('#clear_filter_staff_btn').click();
                }
            }
        });

        // Initialize Employee search for Add Advance Modal
        setupStaffPicker(
            '#add_staff_search_input',
            '#add_staff_id',
            '#add_staff_results_menu',
            '#clear_add_staff_btn',
            null
        );

        // Initialize Employee search for Edit Advance Modal
        setupStaffPicker(
            '#edit_staff_search_input',
            '#edit_staff_id',
            '#edit_staff_results_menu',
            '#clear_edit_staff_btn',
            null
        );

        // Reset Add Advance Modal on hide
        $('#addAdvanceModal').on('hidden.bs.modal', function() {
            $('#addAdvanceForm')[0].reset();
            $('#add_staff_id').val('');
            $('#add_staff_search_input').val('');
            $('#clear_add_staff_btn').hide();
            hideCustomMenu();
        });

        // Reset Edit Modal on hide
        $('#editAdvanceModal').on('hidden.bs.modal', function() {
            hideCustomMenu();
        });

        // Add Advance Form Submit
        $('#addAdvanceForm').on('submit', function(e) {
            e.preventDefault();
            const btn = $('#saveAdvanceBtn');
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');
            
            $.ajax({
                url: '{{ route("admin.advances.store") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res.success) {
                        $('#addAdvanceModal').modal('hide');
                        showSuccessToast(res.message || 'Advance recorded successfully.');
                        setTimeout(() => location.reload(), 1000);
                    }
                },
                error: function(xhr) {
                    let err = 'Failed to save advance.';
                    if (xhr.responseJSON?.errors) {
                        err = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    } else if (xhr.responseJSON?.message) {
                        err = xhr.responseJSON.message;
                    }
                    showErrorToast(err);
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="bx bx-check me-1"></i> Save Advance');
                }
            });
        });

        // Edit Advance Trigger
        $(document).on('click', '.edit-advance', function() {
            const btn = $(this);
            const staffId = btn.data('staff_id');
            $('#edit_advance_id').val(btn.data('id'));
            $('#edit_staff_id').val(staffId);
            
            const staffObj = staffsMap[staffId];
            if (staffObj) {
                const text = staffObj.position ? `${staffObj.name} (${staffObj.position})` : staffObj.name;
                $('#edit_staff_search_input').val(text);
                $('#clear_edit_staff_btn').show();
            } else {
                $('#edit_staff_search_input').val('');
                $('#clear_edit_staff_btn').hide();
            }

            $('#edit_amount').val(btn.data('amount'));
            $('#edit_advance_date').val(btn.data('advance_date'));
            $('#edit_deduction_month').val(btn.data('deduction_month'));
            $('#edit_deduction_year').val(btn.data('deduction_year'));
            $('#edit_status').val(btn.data('status'));
            $('#edit_reason').val(btn.data('reason'));

            $('#editAdvanceModal').modal('show');
        });

        // Reset Edit Modal on hide
        $('#editAdvanceModal').on('hidden.bs.modal', function() {
            hideCustomMenu();
        });

        // Edit Advance Form Submit
        $('#editAdvanceForm').on('submit', function(e) {
            e.preventDefault();
            const id = $('#edit_advance_id').val();
            const btn = $('#updateAdvanceBtn');
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Updating...');

            $.ajax({
                url: '{{ route("admin.advances.update", ":id") }}'.replace(':id', id),
                method: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res.success) {
                        $('#editAdvanceModal').modal('hide');
                        showSuccessToast(res.message || 'Advance updated successfully.');
                        setTimeout(() => location.reload(), 1000);
                    }
                },
                error: function(xhr) {
                    let err = 'Failed to update advance.';
                    if (xhr.responseJSON?.errors) {
                        err = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    } else if (xhr.responseJSON?.message) {
                        err = xhr.responseJSON.message;
                    }
                    showErrorToast(err);
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="bx bx-check me-1"></i> Update Advance');
                }
            });
        });

        // Quick Status Change
        $(document).on('click', '.update-status', function() {
            const id = $(this).data('id');
            const status = $(this).data('status');

            Swal.fire({
                title: 'Change Status?',
                text: `Mark advance status as ${status}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, change',
                customClass: {
                    confirmButton: 'btn btn-primary me-2',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.advances.update-status", ":id") }}'.replace(':id', id),
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            status: status
                        },
                        success: function(res) {
                            if (res.success) {
                                showSuccessToast(res.message);
                                setTimeout(() => location.reload(), 800);
                            }
                        },
                        error: function(xhr) {
                            showErrorToast('Failed to update status.');
                        }
                    });
                }
            });
        });

        // Delete Advance
        $(document).on('click', '.delete-advance', function() {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Delete Advance?',
                text: 'Are you sure you want to delete this employee advance record?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete',
                customClass: {
                    confirmButton: 'btn btn-danger me-2',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.advances.destroy", ":id") }}'.replace(':id', id),
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(res) {
                            if (res.success) {
                                showSuccessToast(res.message);
                                setTimeout(() => location.reload(), 800);
                            }
                        },
                        error: function(xhr) {
                            showErrorToast('Failed to delete advance record.');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
