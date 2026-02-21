@extends('layout')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0 text-dark">
            <i class="fas fa-file-invoice"></i> Purchase Order #{{ $purchaseOrder->po_number }}
        </h1>
    </div>
</div>

<section class="content">
<div class="container-fluid">
<div class="card card-primary card-outline">

<div class="card-header">
    <h3 class="card-title"><i class="fas fa-info-circle"></i> Purchase Order Details</h3>
</div>

<div class="card-body">

    {{-- Supplier & Order Info --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <strong>Supplier:</strong> {{ $purchaseOrder->supplier->name ?? '-' }} <br>
            <strong>Address:</strong> {{ $purchaseOrder->supplier->address ?? '-' }}
        </div>
        <div class="col-md-6">
            <strong>PO Number:</strong> {{ $purchaseOrder->po_number }} <br>
            <strong>Order Date:</strong> {{ $purchaseOrder->order_date->format('d M Y') }}
        </div>
    </div>

    {{-- Products Table --}}
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($purchaseOrder->products as $index => $item)
            @php $total = $item->pivot->quantity * $item->pivot->unit_price; @endphp
            @php $grandTotal += $total; @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->pivot->quantity }}</td>
                <td>{{ number_format($item->pivot->unit_price, 2) }}</td>
                <td>{{ number_format($total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">Grand Total</th>
                <th>{{ number_format($grandTotal, 2) }}</th>
            </tr>
        </tfoot>
    </table>

    {{-- Actions --}}
    <div class="mt-3">
        <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <a href="{{ route('purchase-orders.print', $purchaseOrder->id) }}" class="btn btn-primary" target="_blank">
            <i class="fas fa-print"></i> Print PO
        </a>
    </div>

</div>

</div>
</div>
</section>

@endsection