@extends('layout')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <h4>Company Profile Setup</h4>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('company.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">

                        {{-- ================= BASIC INFO ================= --}}
                        <div class="col-md-6 mb-3">
                            <label>Company Name *</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ $company_info->name ?? 'Medistru ERP' }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ $company_info->email ?? '' }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control"
                                value="{{ $company_info->phone ?? '' }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Website</label>
                            <input type="text" name="website" class="form-control"
                                value="{{ $company_info->website ?? '' }}">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Address</label>
                            <textarea name="address" class="form-control" rows="2">{{ $company_info->address ?? '' }}</textarea>
                        </div>

                        {{-- ================= TAX INFO ================= --}}
                        <div class="col-md-4 mb-3">
                            <label>VAT Number</label>
                            <input type="text" name="vat_number" class="form-control"
                                value="{{ $company_info->vat_number ?? '' }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Tax Number</label>
                            <input type="text" name="tax_number" class="form-control"
                                value="{{ $company_info->tax_number ?? '' }}">
                        </div>

                        <div class="col-md-2 mb-3">
                            <label>Default VAT %</label>
                            <input type="number" step="0.01" name="default_vat_percent" class="form-control"
                                value="{{ $company_info->default_vat_percent ?? 0 }}">
                        </div>

                        <div class="col-md-2 mb-3">
                            <label>Default AIT %</label>
                            <input type="number" step="0.01" name="default_ait_percent" class="form-control"
                                value="{{ $company_info->default_ait_percent ?? 0 }}">
                        </div>

                        {{-- ================= BRANDING ================= --}}
                        <div class="col-md-6 mb-3">
                            <label>Logo</label>
                            <input type="file" name="logo" class="form-control" accept=".ico,.png,.jpg,.jpeg">
                            @if(!empty($company_info->logo))
                                <div class="mt-2">
                                    <img src="{{ asset('storage/'.$company_info->logo) }}?{{ time() }}" width="120">
                                </div>
                            @endif
                        </div>

                        

                        <div class="col-md-6 mb-3">
                            <label>Favicon</label>
                            <input type="file" name="favicon" class="form-control" accept=".ico,.png,.jpg,.jpeg">
                            @if(!empty($company_info->favicon))
                                <div class="mt-2">
                                    <img src="{{ asset('storage/app/public/'.$company_info->favicon) }}" width="40">
                                </div>
                            @endif
                        </div>

                        {{-- ================= DOCUMENT PREFIX ================= --}}
                        <div class="col-md-3 mb-3">
                            <label>Invoice Prefix</label>
                            <input type="text" name="invoice_prefix" class="form-control"
                                value="{{ $company_info->invoice_prefix ?? 'INV-' }}" maxlength="10">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Challan Prefix</label>
                            <input type="text" name="challan_prefix" class="form-control"
                                value="{{ $company_info->challan_prefix ?? 'CN-' }}" maxlength="10">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>PO Prefix</label>
                            <input type="text" name="po_prefix" class="form-control"
                                value="{{ $company_info->po_prefix ?? 'PO-' }}" maxlength="10">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Invoice Start Number</label>
                            <input type="number" name="invoice_start_number" class="form-control"
                                value="{{ $company_info->invoice_start_number ?? 1 }}">
                        </div>

                        {{-- ================= CURRENCY ================= --}}
                        <div class="col-md-4 mb-3">
                            <label>Currency Name</label>
                            <input type="text" name="currency_name" class="form-control"
                                value="{{ $company_info->currency_name ?? 'Taka' }}" maxlength="50">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Currency Symbol</label>
                            <input type="text" name="currency_symbol" class="form-control"
                                value="{{ $company_info->currency_symbol ?? '৳' }}" maxlength="10">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Currency Position</label>
                            <select name="currency_position" class="form-control">
                                <option value="left" {{ ($company_info->currency_position ?? '') == 'left' ? 'selected' : '' }}>Left</option>
                                <option value="right" {{ ($company_info->currency_position ?? '') == 'right' ? 'selected' : '' }}>Right</option>
                            </select>
                        </div>

                        {{-- ================= FOOTER ================= --}}
                        <div class="col-md-6 mb-3">
                            <label>Invoice Footer Title</label>
                            <input type="text" name="invoice_footer_title" class="form-control"
                                value="{{ $company_info->invoice_footer_title ?? '' }}" maxlength="100">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Registration Number</label>
                            <input type="text" name="registration_number" class="form-control"
                                value="{{ $company_info->registration_number ?? '' }}" maxlength="50">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Footer Message</label>
                            <textarea name="footer_message" class="form-control" rows="3">{{ $company_info->footer_message ?? '' }}</textarea>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary">Save Company Information</button>

                </form>

            </div>
        </div>
    </div>
</section>

@endsection