@extends('layout')

@section('content')

<style>
    body {
        background: #f4f6f9;
    }

    .po-container {
        background: #ffffff;
        padding: 40px;
        max-width: 900px;
        margin: auto;
    }

    .pad-header {
        text-align: center;
        margin-bottom: 30px;
        margin-top: 50px;
        border-bottom: 1px solid #ddd;
        padding-bottom: 15px;
    }

    .info-title {
        font-weight: 600;
        margin-bottom: 10px;
    }

    .badge-status {
        padding: 4px 8px;
        font-size: 13px;
    }

    .summary-box {
        margin-top: 30px;
        width: 300px;
        margin-left: auto;
    }

    .signature-row {
        margin-top: 80px;
    }

    .signature-box {
        text-align: center;
    }

    .signature-line {
        border-top: 1px solid #000;
        margin-top: 60px;
        padding-top: 5px;
    }

    @media print {

        body {
            background: #fff !important;
        }

        .po-container {
            box-shadow: none;
            padding: 20px;
        }

        .no-print {
            display: none !important;
        }

        table {
            font-size: 13px;
        }

        .main-footer {
            display: none !important;
        }
    }
</style>

<section class="content">
    <div class="container-fluid">

        <div class="po-container">
            <div class="text-end mb-3 no-print float-right">
                <button onclick="window.print()" class="btn btn-primary btn-sm">
                    Print
                </button>
            </div>
            <div class="pad-header">
                <h3 class="fw-bold">PURCHASE ORDER</h3>
            </div>

            <div class="row mb-4">

                <div class="col-6">
                    <div class="info-title">Supplier Information</div>
                    <p><strong>Name:</strong> {{ $po->supplier->name ?? '-' }}</p>
                    <p><strong>Address:</strong> {{ $po->supplier->address ?? '-' }}</p>
                    <p><strong>Phone:</strong> {{ $po->supplier->responsible_person_contact ?? '-' }}</p>
                </div>
                <div class="col-6">
                    <div class="info-title">Order Information</div>
                    <p><strong>PO No:</strong> {{ $po->po_number }}</p>
                    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($po->order_date)->format('d M Y') }}</p>

                    @php
                    $total = $po->items->sum(fn($item) => $item->quantity * $item->unit_price);
                    $paid = $po->paid_amount ?? 0;
                    $due = $total - $paid;
                    @endphp
                </div>

            </div>

            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th>Product</th>
                        <th>Description</th>
                        <th width="80">Qty</th>
                        <th width="120">Unit Price</th>
                        <th width="120">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($po->items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->product->name ?? '' }}</td>
                        <td>{{ $item->product->description ?? '-' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="summary-box">
                <div class="d-flex justify-content-between">
                    <span>Total</span>
                    <strong>{{ number_format($total, 2) }}</strong>
                </div>

                <div class="d-flex justify-content-between">
                    <span>Paid</span>
                    <strong>{{ number_format($paid, 2) }}</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span>Due</span>
                    <strong class="text-danger">
                        {{ number_format($due, 2) }}
                    </strong>
                </div>
            </div>

            <div class="row signature-row text-center d-flex justify-content-between">

                <div class="col-2 signature-box">
                    <div class="signature-line">Prepared By</div>
                </div>

                <div class="col-2 signature-box">
                    <div class="signature-line">Checked By</div>
                </div>

                <div class="col-2 signature-box">
                    <div class="signature-line">Approved By</div>
                </div>

            </div>

        </div>
    </div>
</section>

@endsection