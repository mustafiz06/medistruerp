@extends('layout')

@section('content')
<section class="content">
<div class="container-fluid">
    <div class="card card-primary card-outline">

        <div class="card-header">
            <h3 class="card-title">Supplier-wise Partial Payment</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('supplier.payment.store') }}" method="POST" id="supplierPaymentForm">
                @csrf

                {{-- Select Supplier --}}
                <div class="form-group">
                    <label>Supplier</label>
                    <select name="supplier_id" id="supplier_id" class="form-control" required>
                        <option value="">-- Select Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }} | Due: {{ number_format($supplier->due_amount,2) }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- PO List Table --}}
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-striped" id="poTable">
                        <thead>
                            <tr>
                                <th>PO Number</th>
                                <th>Total Amount</th>
                                <th>Already Paid</th>
                                <th>Remaining Due</th>
                                <th>Pay Amount</th>
                                <th>Payment Date</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Rows populated dynamically via JS --}}
                        </tbody>
                    </table>
                </div>

                <button type="submit" class="btn btn-success mt-3"><i class="fas fa-money-bill"></i> Submit Payments</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const supplierSelect = document.getElementById('supplier_id');
    const poTableBody = document.querySelector('#poTable tbody');

    supplierSelect.addEventListener('change', function() {
        const supplierId = this.value;
        poTableBody.innerHTML = '';

        if(!supplierId) return;

        fetch(`/supplier/payment-pos/${supplierId}`)
            .then(res => res.json())
            .then(data => {
                data.forEach(po => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${po.po_number}<input type="hidden" name="payments[][po_id]" value="${po.id}"></td>
                        <td>${parseFloat(po.total_amount).toFixed(2)}</td>
                        <td>${parseFloat(po.payments_sum_paid_amount || 0).toFixed(2)}</td>
                        <td>${parseFloat(po.due).toFixed(2)}</td>
                        <td><input type="number" name="payments[][paid_amount]" step="0.01" max="${po.due}" class="form-control"></td>
                        <td><input type="date" name="payments[][payment_date]" value="${new Date().toISOString().slice(0,10)}" class="form-control"></td>
                        <td><input type="text" name="payments[][notes]" class="form-control"></td>
                    `;
                    poTableBody.appendChild(row);
                });
            });
    });
});
</script>
@endsection