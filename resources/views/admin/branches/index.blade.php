@extends('admin.layouts.app')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Branches</h5>
                    @can('create branches')
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#branchModal">Add Branch</button>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                    <table class="table table-hover" id="branchesTable">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Store</th>
                                <th>Branch Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Status</th>
                                @if(auth()->user()->can('edit branches') || auth()->user()->can('delete branches'))
                                <th>Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($branches as $branch)
                            <tr data-id="{{ $branch->id }}">
                                <td></td>
                                <td>{{ $branch->store->store_name ?? 'N/A' }}</td>
                                <td>{{ $branch->name }}</td>
                                <td>{{ $branch->phone }}</td>
                                <td>{{ $branch->email }}</td>
                                <td>
                                    @can('edit branches')
                                    <button class="btn btn-sm toggle-status {{ $branch->status ? 'btn-success' : 'btn-secondary' }}" data-status="{{ $branch->status }}">
                                        {{ $branch->status ? 'Active' : 'Inactive' }}
                                    </button>
                                    @else
                                    <span class="badge {{ $branch->status ? 'bg-label-success' : 'bg-label-secondary' }}">
                                        {{ $branch->status ? 'Active' : 'Inactive' }}
                                    </span>
                                    @endcan
                                </td>
                                @if(auth()->user()->can('edit branches') || auth()->user()->can('delete branches'))
                                <td>
                                    <div class="dropdown">
                                        <button class="btn p-0 dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @can('edit branches')
                                            <li>
                                                <a class="dropdown-item edit-branch" href="javascript:void(0);">
                                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                                </a>
                                            </li>
                                            @endcan
                                            @can('delete branches')
                                            <li>
                                                <a class="dropdown-item text-danger delete-branch" href="javascript:void(0);">
                                                    <i class="bx bx-trash me-1"></i> Delete
                                                </a>
                                            </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                    <div class="mt-3">
                        {{ $branches->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="branchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="branchForm" method="POST">
                @csrf
                <input type="hidden" name="id" id="branchId">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Store <span class="text-danger">*</span></label>
                        <select name="store_id" id="store_id" class="form-select" required>
                            <option value="">Select Store</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger small error-store_id"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control">
                        <span class="text-danger small error-name"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control">
                        <span class="text-danger small error-phone"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                        <span class="text-danger small error-email"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" id="address" class="form-control" rows="2"></textarea>
                        <span class="text-danger small error-address"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <span class="text-danger small error-status"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    var table = $('#branchesTable').DataTable({
        paging: false,
        info: false,
        lengthChange: false,
        order: [[1, 'asc']],
        columnDefs: [{ targets: 0, orderable: false }],
        drawCallback: function() {
            var api = this.api();
            api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }
    });

    $('#branchForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var id = $('#branchId').val();
        var url = id ? '{{ route('admin.branches.update', ':id') }}'.replace(':id', id) : '{{ route('admin.branches.store') }}';
        var type = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData + '&_method=' + type,
            success: function(res) {
                $('#branchModal').modal('hide');
                $('#branchForm')[0].reset();
                setFlesh('success', res.message || 'Branch saved successfully');
                location.reload();
            },
            error: function(xhr) {
                $('#branchForm .text-danger.small').empty();
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        $('.error-' + field).text(messages[0]);
                    });
                } else {
                    var err = xhr.responseJSON?.message || 'Something went wrong';
                    setFlesh('error', err);
                }
            }
        });
    });

    $('#branchesTable tbody').on('click', '.edit-branch', function() {
        var id = $(this).closest('tr').attr('data-id');
        var url = '{{ route('admin.branches.edit', ':id') }}'.replace(':id', id);
        $.ajax({ url: url, dataType: 'json', success: function(data) {
            $('#modalTitle').text('Edit Branch');
            $('#branchId').val(data.id);
            $('#store_id').val(data.store_id);
            $('#name').val(data.name);
            $('#phone').val(data.phone);
            $('#email').val(data.email);
            $('#address').val(data.address);
            $('#status').val(data.status);
            $('#branchModal').modal('show');
        } });
    });

    $('#branchesTable tbody').on('click', '.delete-branch', function() {
        var btn = $(this);
        var id = btn.closest('tr').attr('data-id');
        var url = '{{ route('admin.branches.destroy', ':id') }}'.replace(':id', id);
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}', _method: 'DELETE' },
                    success: function(res) {
                        btn.closest('tr').remove();
                        setFlesh('success', res.message || 'Branch deleted');
                    }
                });
            }
        });
    });

    $('#branchesTable tbody').on('click', '.toggle-status', function() {
        var btn = $(this);
        var id = btn.closest('tr').attr('data-id');
        var url = '{{ route('admin.branches.toggle-status', ':id') }}'.replace(':id', id);
        var status = $(this).attr('data-status');
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to " + (status == 1 ? 'deactivate' : 'activate') + " this branch?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Yes, change it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        if (res.success) {
                            btn.toggleClass('btn-success btn-secondary');
                            btn.text(res.status ? 'Active' : 'Inactive');
                            btn.attr('data-status', res.status);
                            setFlesh('success', res.message);
                        }
                    },
                    error: function(xhr) {
                        setFlesh('error', 'Something went wrong');
                    }
                });
            }
        });
    });

    $('#branchModal').on('hidden.bs.modal', function() {
        $('#modalTitle').text('Add Branch');
        $('#branchForm')[0].reset();
        $('#branchId').val('');
        $('#branchForm .text-danger.small').empty();
    });
});
</script>
@endsection
