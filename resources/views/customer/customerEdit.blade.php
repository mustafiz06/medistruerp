@extends('layout')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Edit Customer</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-primary card-outline">

                    <div class="card-header">
                        <h3 class="card-title mt-1">Edit Customer Information</h3>
                    </div>

                    <div class="card-body">

                        @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                        @endif

                        <form class="form-horizontal" action="{{ route('customer.update', $customer->id) }}" method="POST">
                            @csrf
                            @method('POST')

                            {{-- Customer Type --}}
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Customer Type *</label>
                                <div class="col-sm-10">
                                    <select name="customer_type" id="customer_type" class="form-control" required>
                                        <option value="">-- Select Type --</option>
                                        <option value="individual" {{ old('customer_type', $customer->customer_type) == 'individual' ? 'selected' : '' }}>Individual</option>
                                        <option value="organization" {{ old('customer_type', $customer->customer_type) == 'organization' ? 'selected' : '' }}>Organization</option>
                                    </select>
                                </div>
                            </div>

                            {{-- ================= INDIVIDUAL FIELDS ================= --}}
                            <div id="individual_fields" style="{{ old('customer_type', $customer->customer_type) == 'individual' ? '' : 'display: none;' }}">

                                <div class="form-group row">
                                    <label class="col-sm-2 control-label">Customer Name *</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="name" class="form-control individual-field"
                                            value="{{ old('name', $customer->name) }}" placeholder="Full Name">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 control-label">Designation</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="designation" class="form-control individual-field"
                                            value="{{ old('designation', $customer->designation) }}" placeholder="Designation/Title">
                                    </div>
                                    <label class="col-sm-2 control-label">Gender</label>
                                    <div class="col-sm-4">
                                        <select name="gender" class="form-control individual-field">
                                            <option value="">Select Gender</option>
                                            <option value="male" {{ old('gender', $customer->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender', $customer->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender', $customer->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 control-label">Work Place</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="work_place" class="form-control individual-field"
                                            value="{{ old('work_place', $customer->work_place) }}" placeholder="Company/Institution Name">
                                    </div>
                                </div>

                                {{-- Assistant Contact --}}
                                <div class="form-group row">
                                    <label class="col-sm-2 control-label">Assistant Contact</label>
                                    <div class="col-sm-10">
                                        <button type="button" class="btn btn-sm btn-default" id="toggle_assistant">
                                            {{ $customer->assistant_name ? '- Remove Assistant' : '+ Add Assistant' }}
                                        </button>
                                    </div>
                                </div>

                                <div id="assistant_fields" style="{{ $customer->assistant_name ? '' : 'display: none;' }}; border: 1px solid #ddd; padding: 15px; margin: 10px 0; background: #f9f9f9;">

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Assistant Name</label>
                                        <div class="col-sm-5">
                                            <input type="text" name="assistant_name" class="form-control"
                                                value="{{ old('assistant_name', $customer->assistant_name) }}">
                                        </div>
                                        <label class="col-sm-2 control-label">Assistant Phone</label>
                                        <div class="col-sm-3">
                                            <input type="text" name="assistant_phone" class="form-control"
                                                value="{{ old('assistant_phone', $customer->assistant_phone) }}">
                                        </div>
                                    </div>

                                </div>

                            </div>
                            {{-- ================= END INDIVIDUAL ================= --}}


                            {{-- ================= ORGANIZATION FIELDS ================= --}}
                            <div id="organization_fields" style="{{ old('customer_type', $customer->customer_type) == 'organization' ? '' : 'display: none;' }}">

                                <div class="form-group row">
                                    <label class="col-sm-2 control-label">Company Name *</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="company_name" class="form-control organization-field"
                                            value="{{ old('company_name', $customer->company_name) }}" placeholder="Registered Company Name">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 control-label">Contact Person *</label>
                                    <div class="col-sm-5">
                                        <input type="text" name="contact_person" class="form-control organization-field"
                                            value="{{ old('contact_person', $customer->contact_person) }}">
                                    </div>
                                    <label class="col-sm-2 control-label">Position</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="contact_person_position" class="form-control organization-field"
                                            value="{{ old('contact_person_position', $customer->contact_person_position) }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 control-label">Contact Person Phone</label>
                                    <div class="col-sm-5">
                                        <input type="text" name="contact_person_phone" class="form-control organization-field"
                                            value="{{ old('contact_person_phone', $customer->contact_person_phone) }}">
                                    </div>
                                    <label class="col-sm-2 control-label">BIN No</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="bin_no" class="form-control organization-field"
                                            value="{{ old('bin_no', $customer->bin_no) }}" placeholder="Business ID">
                                    </div>
                                </div>

                            </div>
                            {{-- ================= END ORGANIZATION ================= --}}


                            {{-- ============ COMMON FIELDS (Both Types) ============ --}}

                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $customer->email) }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Phone *</label>
                                <div class="col-sm-10">
                                    <input type="text" name="phone" class="form-control"
                                        value="{{ old('phone', $customer->phone) }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Address</label>
                                <div class="col-sm-10">
                                    <textarea name="address" class="form-control" rows="3">{{ old('address', $customer->address) }}</textarea>
                                </div>
                            </div>

                            {{-- Financial Section --}}
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Credit Limit</label>
                                <div class="col-sm-4">
                                    <input type="number" step="0.01" name="credit_limit"
                                        class="form-control" value="{{ old('credit_limit', $customer->credit_limit ?? 0) }}" min="0">
                                    <small class="text-muted">Maximum credit allowed</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Priority</label>
                                <div class="col-sm-4">
                                    <select name="priority" class="form-control">
                                        <option value="normal" {{ old('priority', $customer->priority ?? 'normal') == 'normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="high" {{ old('priority', $customer->priority) == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="low" {{ old('priority', $customer->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                    </select>
                                </div>
                                <label class="col-sm-2 control-label">Sales Representative</label>
                                <div class="col-sm-4">
                                    <select name="sales_representative_id" class="form-control">
                                        <option value="1" {{ old('sales_representative_id', $customer->sales_representative_id ?? 1) == 1 ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ old('sales_representative_id', $customer->sales_representative_id) == 2 ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ old('sales_representative_id', $customer->sales_representative_id) == 3 ? 'selected' : '' }}>3</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Status *</label>
                                <div class="col-sm-4">
                                    <select name="status" class="form-control" required>
                                        <option value="active" {{ old('status', $customer->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $customer->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="suspended" {{ old('status', $customer->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Notes --}}
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Notes</label>
                                <div class="col-sm-10">
                                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $customer->notes) }}</textarea>
                                    <small class="text-muted">Additional information about this customer</small>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="form-group row">
                                <div class="offset-sm-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Customer
                                    </button>
                                    <a href="{{ route('customer.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Toggle Script (Inside content section) --}}
<script>
    (function() {
        'use strict';

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }

        function init() {
            const typeSelect = document.getElementById("customer_type");
            const individual = document.getElementById("individual_fields");
            const organization = document.getElementById("organization_fields");
            const assistantToggle = document.getElementById("toggle_assistant");
            const assistantFields = document.getElementById("assistant_fields");

            if (!typeSelect || !individual || !organization) {
                return;
            }

            // Initialize fields based on current customer type
            toggleFields();

            typeSelect.addEventListener("change", toggleFields);

            function toggleFields() {
                const selectedType = typeSelect.value;

                if (selectedType === "individual") {
                    individual.style.display = "block";
                    organization.style.display = "none";
                    disableInputs(organization, true);
                    disableInputs(individual, false);
                    setRequired(individual, true);
                    setRequired(organization, false);

                } else if (selectedType === "organization") {
                    individual.style.display = "none";
                    organization.style.display = "block";
                    disableInputs(individual, true);
                    disableInputs(organization, false);
                    setRequired(organization, true);
                    setRequired(individual, false);

                } else {
                    individual.style.display = "none";
                    organization.style.display = "none";
                    disableInputs(individual, true);
                    disableInputs(organization, true);
                    setRequired(individual, false);
                    setRequired(organization, false);
                }
            }

            function disableInputs(container, state) {
                const inputs = container.querySelectorAll("input, select, textarea");
                inputs.forEach(function(input) {
                    input.disabled = state;
                    if (state) {
                        input.removeAttribute('required');
                    }
                });
            }

            function setRequired(container, state) {
                const inputs = container.querySelectorAll("input, select, textarea");
                inputs.forEach(function(input) {
                    if (state) {
                        if (input.name === 'name' || input.name === 'company_name' ||
                            input.name === 'contact_person' || input.name === 'phone') {
                            input.setAttribute('required', 'required');
                        }
                    } else {
                        input.removeAttribute('required');
                    }
                });
            }

            if (assistantToggle && assistantFields) {
                assistantToggle.addEventListener("click", function(e) {
                    e.preventDefault();
                    if (assistantFields.style.display === "none" || assistantFields.style.display === "") {
                        assistantFields.style.display = "block";
                        this.textContent = "- Remove Assistant";
                    } else {
                        assistantFields.style.display = "none";
                        this.textContent = "+ Add Assistant";
                        const inputs = assistantFields.querySelectorAll("input");
                        inputs.forEach(function(input) {
                            input.value = '';
                        });
                    }
                });
            }
        }
    })();
</script>

@endsection