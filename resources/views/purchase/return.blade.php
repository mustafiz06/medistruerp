@extends('layout')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0 text-dark">Purchase Return</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        <div class="card card-warning card-outline shadow-sm">
            <div class="card-header bg-success">
                <h3 class="card-title">Return Against Purchase Order No: <strong>{{ $po->po_number }}</strong></h3>
            </div>

            <div class="card-body">

                <div class="row mb-4">
                    <div class="col-md-4"><strong>Supplier:</strong> {{ $po->supplier->name }}</div>
                    <div class="col-md-4"><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($po->order_date)->format('d M Y') }}</div>
                    <div class="col-md-4"><strong>Total Amount:</strong> ৳ {{ number_format($po->total_amount,2) }}</div>
                </div>

                <form action="{{ route('po.return.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="purchase_order_id" value="{{ $po->id }}">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Purchased Qty</th>
                                    <th>Returned Qty</th>
                                    <th>Remaining Qty</th>
                                    <th>Unit Price</th>
                                    <th>Return Qty</th>
                                    <th>Return Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($po->items as $item)
                                @php
                                $returnedQty = $po->returns ? $po->returns->where('product_id', $item->product_id)->sum('quantity') : 0;
                                $remaining = $item->quantity - $returnedQty;
                                @endphp
                                <tr>
                                    <td class="text-start">{{ $item->product->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $returnedQty }}</td>
                                    <td>
                                        @if($remaining > 0)
                                        <span>{{ $remaining }}</span>
                                        @else
                                        <span>0</span>
                                        @endif
                                    </td>
                                    <td>৳ {{ number_format($item->unit_price,2) }}</td>
                                    <td>
                                        <input type="number"
                                            name="products[{{ $item->product_id }}][quantity]"
                                            class="form-control return-qty"
                                            min="0"
                                            max="{{ $remaining }}"
                                            step="1"
                                            placeholder="0"
                                            data-price="{{ $item->unit_price }}"
                                            {{ $remaining <= 0 ? 'disabled' : '' }}>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control row-total text-end" readonly>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Grand Total & Reason --}}
                    <div class="row mt-4">
                        {{-- Left Side: Return Reason --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label"><strong>Return Reason</strong></label>
                                <textarea name="reason" class="form-control" rows="5" placeholder="Enter reason for return..."></textarea>
                            </div>
                        </div>

                        {{-- Right Side: Grand Total & Submit Button --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label"><strong>Grand Return Total</strong></label>
                                <input type="text" id="grand-total" class="form-control form-control-lg text-end fw-bold" readonly>
                            </div>

                            <button type="submit" class="btn btn-success btn-md shadow-sm" >
                                <i class="fas fa-undo-alt me-2"></i> Submit Purchase Return
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>

    </div>
</section>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Calculate totals on page load
        $('.return-qty').trigger('change');
    });

    $(document).on('keyup change', '.return-qty', function() {
        let qty = parseFloat($(this).val()) || 0;
        let price = parseFloat($(this).data('price')) || 0;
        let total = qty * price;
        $(this).closest('tr').find('.row-total').val(total.toFixed(2));
        calculateGrandTotal();
    });

    function calculateGrandTotal() {
        let grandTotal = 0;
        $('.row-total').each(function() {
            grandTotal += parseFloat($(this).val()) || 0;
        });
        $('#grand-total').val(grandTotal.toFixed(2));
    }
</script>
@endsection