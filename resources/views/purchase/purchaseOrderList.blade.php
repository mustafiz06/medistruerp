@extends('layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Purchase Orders</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Purchase Orders</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        {{-- Summary Cards --}}
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-file-invoice"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total POs</span>
                        <span class="info-box-number">{{ $purchaseOrders->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pending</span>
                        <span class="info-box-number">{{ $purchaseOrders->where('status', 'pending')->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-times-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Cancelled</span>
                        <span class="info-box-number">{{ $purchaseOrders->where('status', 'cancel')->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Completed</span>
                        <span class="info-box-number">{{ $purchaseOrders->where('status', 'completed')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Purchase Order List</h3>
                <div class="card-tools">
                    <a href="{{ route('po.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> Create New PO
                    </a>
                </div>
            </div>

            <div class="card-body">
                {{-- Filters --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Filter by Status</label>
                            <select class="form-control form-control-sm" id="status-filter">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="cancel">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Filter by Supplier</label>
                            <select class="form-control form-control-sm" id="supplier-filter">
                                <option value="">All Suppliers</option>
                                @foreach($purchaseOrders->unique('supplier_id') as $po)
                                    @if($po->supplier)
                                        <option value="{{ $po->supplier_id }}">{{ $po->supplier->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Date Range</label>
                            <input type="text" class="form-control form-control-sm" id="date-range" placeholder="Select range">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button class="btn btn-primary btn-sm btn-block" onclick="applyFilters()">
                                <i class="fas fa-filter"></i> Apply Filters
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="po-table">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="12%">PO Number</th>
                                <th width="10%">Date</th>
                                <th width="18%">Supplier</th>
                                <th width="12%" class="text-right">Total Amount</th>
                                <th width="12%" class="text-right">Paid</th>
                                <th width="12%" class="text-right">Due</th>
                                <th width="10%">Status</th>
                                <th width="19%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchaseOrders as $index => $po)
                            <tr data-status="{{ $po->status }}" data-supplier="{{ $po->supplier_id }}">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <a href="{{ route('po.view', $po->id) }}" class="font-weight-bold">
                                        {{ $po->po_number }}
                                    </a>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($po->order_date)->format('d M Y') }}</td>
                                <td>
                                    @if($po->supplier)
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm rounded-circle bg-info text-white d-flex align-items-center justify-content-center mr-2" style="width: 32px; height: 32px;">
                                                <small>{{ substr($po->supplier->name, 0, 1) }}</small>
                                            </div>
                                            <div>
                                                <div class="font-weight-medium">{{ $po->supplier->name }}</div>
                                                @if($po->supplier->phone)
                                                    <small class="text-muted">{{ $po->supplier->phone }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td class="text-right font-weight-bold">
                                    ${{ number_format($po->total_amount, 2) }}
                                </td>
                                <td class="text-right text-success">
                                    ${{ number_format($po->paid_amount ?? 0, 2) }}
                                </td>
                                <td class="text-right text-danger font-weight-bold">
                                    ${{ number_format($po->due_amount ?? $po->total_amount, 2) }}
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm dropdown-toggle 
                                            {{ $po->status == 'completed' ? 'btn-success' : 
                                               ($po->status == 'cancel' ? 'btn-danger' : 'btn-warning') }}"
                                            type="button" 
                                            id="statusDropdown{{ $po->id }}" 
                                            data-toggle="dropdown" 
                                            aria-haspopup="true" 
                                            aria-expanded="false"
                                            style="min-width: 100px;">
                                            @if($po->status == 'completed')
                                                <i class="fas fa-check-circle"></i> Completed
                                            @elseif($po->status == 'cancel')
                                                <i class="fas fa-times-circle"></i> Cancelled
                                            @else
                                                <i class="fas fa-clock"></i> Pending
                                            @endif
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="statusDropdown{{ $po->id }}">
                                            <a class="dropdown-item {{ $po->status == 'pending' ? 'active' : '' }}" 
                                               href="#" 
                                               onclick="changeStatus({{ $po->id }}, 'pending')">
                                                <i class="fas fa-clock text-warning"></i> Pending
                                            </a>
                                            <a class="dropdown-item {{ $po->status == 'completed' ? 'active' : '' }}" 
                                               href="#" 
                                               onclick="changeStatus({{ $po->id }}, 'completed')">
                                                <i class="fas fa-check-circle text-success"></i> Completed
                                            </a>
                                            <a class="dropdown-item {{ $po->status == 'cancel' ? 'active' : '' }}" 
                                               href="#" 
                                               onclick="changeStatus({{ $po->id }}, 'cancel')">
                                                <i class="fas fa-times-circle text-danger"></i> Cancelled
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <small class="dropdown-item-text text-muted">Click to change status</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('po.view', $po->id) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="View Details"
                                           data-toggle="tooltip">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        @if(!in_array($po->status, ['completed', 'cancel']))
                                        <a href="{{ route('po.edit', $po->id) }}" 
                                           class="btn btn-primary btn-sm" 
                                           title="Edit"
                                           data-toggle="tooltip">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        @endif
                                        <a href="{{ route('po.return.form', $po->id) }}" 
                                           class="btn btn-warning btn-sm" 
                                           title="Return Items"
                                           data-toggle="tooltip">
                                            <i class="fas fa-undo"></i> Return
                                        </a>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm" 
                                                title="Delete"
                                                data-toggle="tooltip"
                                                onclick="confirmDelete({{ $po->id }}, '{{ $po->po_number }}')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>No purchase orders found.</p>
                                    <a href="{{ route('po.create') }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-plus"></i> Create Your First PO
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="font-weight-bold bg-light">
                                <td colspan="4" class="text-right">Total:</td>
                                <td class="text-right">${{ number_format($purchaseOrders->sum('total_amount'), 2) }}</td>
                                <td class="text-right text-success">${{ number_format($purchaseOrders->sum('paid_amount'), 2) }}</td>
                                <td class="text-right text-danger">${{ number_format($purchaseOrders->sum('due_amount'), 2) }}</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Pagination --}}
                @if(method_exists($purchaseOrders, 'links'))
                    <div class="mt-3">
                        {{ $purchaseOrders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Status Change Modal --}}
<div class="modal fade" id="statusChangeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form id="statusChangeForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-sync-alt"></i> Change Status</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="po_id" id="modal-po-id">
                    <div class="form-group">
                        <label>New Status</label>
                        <select name="status" class="form-control" required>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="cancel">Cancelled</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Notes (Optional)</label>
                        <textarea name="status_notes" class="form-control" rows="2" placeholder="Reason for status change..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    .info-box {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        border-radius: 0.25rem;
    }
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .btn-group .btn {
        margin: 0 1px;
        border-radius: 0.2rem;
    }
    .btn-group .btn:first-child {
        border-top-left-radius: 0.2rem;
        border-bottom-left-radius: 0.2rem;
    }
    .btn-group .btn:last-child {
        border-top-right-radius: 0.2rem;
        border-bottom-right-radius: 0.2rem;
    }
    .avatar-sm {
        font-size: 0.75rem;
        font-weight: 600;
    }
    .dropdown-menu {
        min-width: 180px;
    }
    .dropdown-item i {
        width: 20px;
    }
    .dropdown-item.active {
        background-color: #e9ecef;
        color: #495057;
    }
</style>
@endsection

@section('script')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Initialize tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    // Status Change Function
    function changeStatus(poId, newStatus) {
        $('#modal-po-id').val(poId);
        $('#statusChangeForm select[name="status"]').val(newStatus);
        $('#statusChangeForm').attr('action', '/purchase-orders/status/' + poId);
        $('#statusChangeModal').modal('show');
    }

    // Delete Confirmation with SweetAlert
    function confirmDelete(poId, poNumber) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to delete Purchase Order: " + poNumber + ". This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ url('purchase-orders/destroy') }}/" + poId;
                form.innerHTML = `@csrf @method('POST')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Filter Functions
    function applyFilters() {
        let status = $('#status-filter').val();
        let supplier = $('#supplier-filter').val();
        
        $('#po-table tbody tr').each(function() {
            let rowStatus = $(this).data('status');
            let rowSupplier = $(this).data('supplier');
            let showRow = true;
            
            if (status && rowStatus !== status) showRow = false;
            if (supplier && rowSupplier != supplier) showRow = false;
            
            $(this).toggle(showRow);
        });
    }

    $('#status-filter, #supplier-filter').change(function() {
        if (!$(this).val()) applyFilters();
    });

    // Status change form submit
    $('#statusChangeForm').submit(function(e) {
        e.preventDefault();
        let form = $(this);
        let submitBtn = form.find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                $('#statusChangeModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error updating status. Please try again.'
                });
                submitBtn.prop('disabled', false).html('Update Status');
            }
        });
    });

    // DataTable initialization
    $(document).ready(function() {
        if ($.fn.DataTable) {
            $('#po-table').DataTable({
                "pageLength": 10,
                "order": [[ 1, "desc" ]],
                "columnDefs": [{ "orderable": false, "targets": [8] }],
                "language": {
                    "search": "Filter:",
                    "paginate": {
                        "previous": "‹ Previous",
                        "next": "Next ›"
                    }
                }
            });
        }
    });
</script>
@endsection