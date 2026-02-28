@extends('layout')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-md-6">
                <h3 class="m-0 text-dark">{{ __('Customer List') }}</h3>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('customer.add') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> {{ __('Add Customer') }}
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">

                    {{-- Card Body - Table --}}
                    <div class="card-body table-responsive p-0">
                        <table class="table table-bordered table-striped table-hover data_table" id="customerTable">
                            <thead class="thead-info">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="12%">Code</th>
                                    <th width="15%">Name/Company</th>
                                    <th width="10%">Type</th>
                                    <th width="12%">Contact</th>
                                    <th width="12%">Assistant</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Credit</th>
                                    <th width="14%" class="text-center">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($customers as $index => $customer)
                                <tr>
                                    {{-- Serial Number --}}
                                    <td>{{ $loop->iteration }}</td>

                                    {{-- Customer Code --}}
                                    <td>
                                        <small class="text-muted">{{ e($customer->customer_code) }}</small>
                                    </td>

                                    {{-- Name / Company --}}
                                    <td>
                                        <strong>
                                            @if($customer->customer_type === 'individual')
                                                {{ e($customer->name ?? 'N/A') }}
                                                @if($customer->designation)
                                                    <br><small class="text-muted">{{ e($customer->designation) }}</small>
                                                @endif
                                            @else
                                                {{ e($customer->company_name ?? 'N/A') }}
                                                @if($customer->contact_person)
                                                    <br><small class="text-muted">Contact: {{ e($customer->contact_person) }}</small>-
                                                @endif
                                                @if($customer->contact_person_position)
                                                    <br><small class="text-muted">{{ e($customer->contact_person_position) }}</small>
                                                @endif
                                            @endif
                                        </strong>
                                    </td>

                                    {{-- Customer Type Badge --}}
                                    <td>
                                        @if($customer->customer_type === 'individual')
                                            <span class="badge badge-success">Individual</span>
                                        @else
                                            <span class="badge badge-info">Organization</span>
                                        @endif
                                    </td>

                                    {{-- Contact Info --}}
                                    <td>
                                        @if($customer->phone)
                                            <i class="fas fa-phone text-muted mr-1"></i>
                                            <a href="tel:{{ e($customer->phone) }}">{{ e($customer->phone) }}</a>
                                        @endif
                                        @if($customer->email)
                                            <br>
                                            <i class="fas fa-envelope text-muted mr-1"></i>
                                            <a href="mailto:{{ e($customer->email) }}">{{ e(\Illuminate\Support\Str::limit($customer->email, 20)) }}</a>
                                        @endif
                                    </td>

                                    {{-- Assistant Info --}}
                                    <td>
                                        @if($customer->assistant_name)
                                            <i class="fas fa-user-friends text-muted mr-1"></i>
                                            {{ e($customer->assistant_name) }}
                                            @if($customer->assistant_phone)
                                                <br>
                                                <small>
                                                    <a href="tel:{{ e($customer->assistant_phone) }}">
                                                        {{ e($customer->assistant_phone) }}
                                                    </a>
                                                </small>
                                            @endif
                                            
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>

                                    {{-- Status Badge --}}
                                    <td>
                                        @switch($customer->status)
                                            @case('active')
                                                <span class="badge badge-success">Active</span>
                                                @break
                                            @case('inactive')
                                                <span class="badge badge-secondary">Inactive</span>
                                                @break
                                            @case('suspended')
                                                <span class="badge badge-warning">Suspended</span>
                                                @break
                                            @default
                                                <span class="badge badge-light">{{ e($customer->status) }}</span>
                                        @endswitch
                                    </td>

                                    {{-- Credit Info --}}
                                    <td>
                                        <small>
                                            <strong>Limit:</strong> {{ number_format($customer->credit_limit ?? 0, 2) }}<br>
                                            <strong>Due:</strong>
                                            @if(($customer->due_amount ?? 0) > 0)
                                                <span class="text-danger">{{ number_format($customer->due_amount, 2) }}</span>
                                            @else
                                                <span class="text-success">0.00</span>
                                            @endif
                                        </small>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            {{-- View Button (Opens Modal) --}}
                                            <button type="button"
                                                class="btn btn-default view-customer"
                                                title="View"
                                                data-toggle="tooltip"
                                                data-id="{{ $customer->id }}"
                                                data-code="{{ e($customer->customer_code) }}"
                                                data-type="{{ e($customer->customer_type) }}"
                                                data-name="{{ e($customer->customer_type === 'individual' ? ($customer->name ?? '') : ($customer->company_name ?? '')) }}"
                                                data-designation="{{ e($customer->designation) }}"
                                                data-contact-person="{{ e($customer->contact_person) }}"
                                                data-contact-position="{{ e($customer->contact_person_position) }}"
                                                data-phone="{{ e($customer->phone) }}"
                                                data-email="{{ e($customer->email) }}"
                                                data-address="{{ e($customer->address) }}"
                                                data-city="{{ e($customer->city ?? '') }}"
                                                data-state="{{ e($customer->state ?? '') }}"
                                                data-country="{{ e($customer->country ?? '') }}"
                                                data-assistant-name="{{ e($customer->assistant_name) }}"
                                                data-assistant-phone="{{ e($customer->assistant_phone) }}"
                                                data-bin-no="{{ e($customer->bin_no) }}"
                                                data-work-place="{{ e($customer->work_place) }}"
                                                data-gender="{{ e($customer->gender) }}"
                                                data-credit-limit="{{ $customer->credit_limit ?? 0 }}"
                                                data-outstanding="{{ $customer->outstanding_balance ?? 0 }}"
                                                data-due="{{ $customer->due_amount ?? 0 }}"
                                                data-available="{{ $customer->available_credit ?? 0 }}"
                                                data-payment-terms="{{ e($customer->payment_terms ?? 'Cash') }}"
                                                data-status="{{ e($customer->status) }}"
                                                data-priority="{{ e($customer->priority) }}"
                                                data-notes="{{ e($customer->notes) }}"
                                                data-created="{{ $customer->created_at?->format('Y-m-d H:i') }}"
                                                data-updated="{{ $customer->updated_at?->format('Y-m-d H:i') }}"
                                                data-edit-url="{{ route('customer.edit.view', $customer->id) }}">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            {{-- Edit --}}
                                            <a href="{{ route('customer.edit.view', $customer->id) }}"
                                                class="btn btn-info"
                                                title="Edit"
                                                data-toggle="tooltip">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>

                                            {{-- Delete --}}
                                            

                                            <form id="deleteform" class="d-inline-block" action="{{ route('customer.delete', $customer->id ) }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $customer->id }}">
                                                <button type="submit" class="btn btn-danger btn-sm" id="delete" disabled>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No customers found.</p>
                                        <a href="{{ route('customer.add') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus"></i> Add First Customer
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination (if using Laravel paginator) --}}
                    @if(method_exists($customers, 'links'))
                        <div class="card-footer clearfix">
                            {{ $customers->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== CUSTOMER VIEW MODAL ========== --}}
<div class="modal fade" id="customerViewModal" tabindex="-1" role="dialog" aria-labelledby="customerViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            
            {{-- Modal Header --}}
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="customerViewModalLabel">
                    <i class="fas fa-user mr-2"></i><span id="modal_customer_name">Customer Details</span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            {{-- Modal Body --}}
            <div class="modal-body">
                
                {{-- Customer Type Badge --}}
                <div class="mb-3">
                    <span id="modal_customer_type" class="badge badge-lg badge-info">Individual</span>
                    <small class="text-muted ml-2">Code: <strong id="modal_customer_code"></strong></small>
                </div>
                
                {{-- Basic Information --}}
                <h6 class="font-weight-bold border-bottom pb-2 mb-3">
                    <i class="fas fa-info-circle mr-2"></i>Basic Information
                </h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Name / Company</label>
                        <p class="font-weight-bold mb-1" id="modal_name"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Designation / Position</label>
                        <p class="mb-1" id="modal_designation">—</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Gender</label>
                        <p class="mb-1" id="modal_gender">—</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Work Place</label>
                        <p class="mb-1" id="modal_work_place">—</p>
                    </div>
                </div>
                
                {{-- Organization Specific --}}
                <div id="org_fields" style="display: none;">
                    <h6 class="font-weight-bold border-bottom pb-2 mb-3">
                        <i class="fas fa-building mr-2"></i>Organization Details
                    </h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Contact Person</label>
                            <p class="mb-1" id="modal_contact_person"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Position</label>
                            <p class="mb-1" id="modal_contact_position"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">BIN / Tax ID</label>
                            <p class="mb-1" id="modal_bin_no"></p>
                        </div>
                    </div>
                </div>
                
                {{-- Contact Information --}}
                <h6 class="font-weight-bold border-bottom pb-2 mb-3">
                    <i class="fas fa-address-book mr-2"></i>Contact Information
                </h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Phone</label>
                        <p class="mb-1">
                            <a href="tel:" id="modal_phone_link"><span id="modal_phone"></span></a>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Email</label>
                        <p class="mb-1">
                            <a href="mailto:" id="modal_email_link"><span id="modal_email"></span></a>
                        </p>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small">Address</label>
                        <p class="mb-1" id="modal_address"></p>
                        <small class="text-muted" id="modal_location"></small>
                    </div>
                </div>
                
                {{-- Assistant Information --}}
                <h6 class="font-weight-bold border-bottom pb-2 mb-3">
                    <i class="fas fa-user-friends mr-2"></i>Assistant / Representative
                </h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Assistant Name</label>
                        <p class="mb-1" id="modal_assistant_name">—</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Assistant Phone</label>
                        <p class="mb-1">
                            <a href="tel:" id="modal_assistant_phone_link"><span id="modal_assistant_phone"></span></a>
                        </p>
                    </div>
                </div>
                
                {{-- Financial Information --}}
                <h6 class="font-weight-bold border-bottom pb-2 mb-3">
                    <i class="fas fa-wallet mr-2"></i>Financial Information
                </h6>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="text-muted small">Credit Limit</label>
                        <p class="font-weight-bold mb-1" id="modal_credit_limit"></p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Outstanding</label>
                        <p class="mb-1 text-warning" id="modal_outstanding"></p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Due Amount</label>
                        <p class="mb-1" id="modal_due"></p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Available Credit</label>
                        <p class="mb-1 text-success" id="modal_available"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Payment Terms</label>
                        <p class="mb-1" id="modal_payment_terms"></p>
                    </div>
                </div>
                
                {{-- Classification --}}
                <h6 class="font-weight-bold border-bottom pb-2 mb-3">
                    <i class="fas fa-tags mr-2"></i>Classification
                </h6>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="text-muted small">Status</label>
                        <p class="mb-1"><span id="modal_status" class="badge"></span></p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Priority</label>
                        <p class="mb-1" id="modal_priority"></p>
                    </div>
                </div>
                
                {{-- Notes --}}
                <h6 class="font-weight-bold border-bottom pb-2 mb-3">
                    <i class="fas fa-sticky-note mr-2"></i>Notes
                </h6>
                <div class="alert alert-light border">
                    <p class="mb-0" id="modal_notes"></p>
                </div>
                
                {{-- Timestamps --}}
                <div class="row small text-muted border-top pt-3">
                    <div class="col-6">
                        <i class="fas fa-plus-circle mr-1"></i>
                        Created: <span id="modal_created"></span>
                    </div>
                    <div class="col-6 text-right">
                        <i class="fas fa-edit mr-1"></i>
                        Updated: <span id="modal_updated"></span>
                    </div>
                </div>
                
            </div>
            
            {{-- Modal Footer --}}
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Close
                </button>
                <a href="#" id="modal_edit_btn" class="btn btn-info">
                    <i class="fas fa-pencil-alt mr-1"></i>Edit Customer
                </a>
            </div>
            
        </div>
    </div>
</div>
{{-- ========== END MODAL ========== --}}

@endsection

@section('script')
<script>


function formatMoney(amount) {
    const num = parseFloat(amount || 0);
    return isNaN(num) ? '0.00' : num.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

//  modal 
function populateCustomerModal(btn) {
    const ds = btn.dataset;
    
    // Basic 
    document.getElementById('modal_customer_name').textContent = ds.name || 'N/A';
    document.getElementById('modal_customer_code').textContent = ds.code || 'N/A';
    document.getElementById('modal_name').textContent = ds.name || 'N/A';
    document.getElementById('modal_designation').textContent = ds.designation || '—';
    document.getElementById('modal_gender').textContent = ds.gender ? ds.gender.charAt(0).toUpperCase() + ds.gender.slice(1) : '—';
    document.getElementById('modal_work_place').textContent = ds.workPlace || '—';
    
    // Contact
    document.getElementById('modal_phone').textContent = ds.phone || '—';
    document.getElementById('modal_phone_link').href = ds.phone ? 'tel:' + ds.phone : '#';
    document.getElementById('modal_email').textContent = ds.email || '—';
    document.getElementById('modal_email_link').href = ds.email ? 'mailto:' + ds.email : '#';
    document.getElementById('modal_address').textContent = ds.address || '—';
    const locationParts = [ds.city, ds.state, ds.country].filter(function(part) { return part && part.trim() !== ''; });
    document.getElementById('modal_location').textContent = locationParts.length ? locationParts.join(', ') : '';
    
    // Organization 
    const orgFields = document.getElementById('org_fields');
    if (ds.type === 'organization') {
        orgFields.style.display = 'block';
        document.getElementById('modal_contact_person').textContent = ds.contactPerson || '—';
        document.getElementById('modal_contact_position').textContent = ds.contactPosition || '—';
        document.getElementById('modal_bin_no').textContent = ds.binNo || '—';
    } else {
        orgFields.style.display = 'none';
    }
    
    // Assistant
    document.getElementById('modal_assistant_name').textContent = ds.assistantName || '—';
    document.getElementById('modal_assistant_phone').textContent = ds.assistantPhone || '—';
    document.getElementById('modal_assistant_phone_link').href = ds.assistantPhone ? 'tel:' + ds.assistantPhone : '#';
    
    // account
    document.getElementById('modal_credit_limit').textContent = formatMoney(ds.creditLimit);
    document.getElementById('modal_outstanding').textContent = formatMoney(ds.outstanding);
    document.getElementById('modal_due').textContent = formatMoney(ds.due);
    document.getElementById('modal_available').textContent = formatMoney(ds.available);
    document.getElementById('modal_payment_terms').textContent = ds.paymentTerms || 'Cash';
    
    const dueEl = document.getElementById('modal_due');
    if (parseFloat(ds.due) > 0) {
        dueEl.className = 'mb-1 text-danger font-weight-bold';
    } else {
        dueEl.className = 'mb-1 text-success';
    }
    
    const status = ds.status || '';
    const statusEl = document.getElementById('modal_status');
    statusEl.textContent = status ? status.charAt(0).toUpperCase() + status.slice(1) : 'N/A';
    
    let badgeClass = 'light';
    if (status === 'active') badgeClass = 'success';
    else if (status === 'inactive') badgeClass = 'secondary';
    else if (status === 'suspended') badgeClass = 'warning';
    
    statusEl.className = 'badge badge-' + badgeClass;
    
    document.getElementById('modal_priority').textContent = ds.priority ? ds.priority.charAt(0).toUpperCase() + ds.priority.slice(1) : 'Normal';
    
    document.getElementById('modal_notes').textContent = ds.notes || 'No notes available.';
    document.getElementById('modal_created').textContent = ds.created || 'N/A';
    document.getElementById('modal_updated').textContent = ds.updated || 'N/A';
    const typeEl = document.getElementById('modal_customer_type');
    const isIndividual = ds.type === 'individual';
    typeEl.textContent = isIndividual ? 'Individual' : 'Organization';
    typeEl.className = 'badge badge-lg badge-' + (isIndividual ? 'success' : 'info');
    
    document.getElementById('modal_edit_btn').href = ds.editUrl || '#';
}

$(document).ready(function() {
    
    $('[data-toggle="tooltip"]').tooltip();
    
    $('#customerTable').on('click', '.view-customer', function(e) {
        e.preventDefault();
        populateCustomerModal(this);
        $('#customerViewModal').modal('show');
    });
    
    $('#customerViewModal').on('hidden.bs.modal', function () {
    });
});
</script>
@endsection