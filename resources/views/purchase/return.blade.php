@extends('layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0 text-dark">
            <i class="fas fa-undo-alt me-2"></i>Purchase Return
        </h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        @if(session('notification'))
        <div class="alert alert-{{ session('notification.alert') }} alert-dismissible fade show shadow-sm" role="alert">
            {{ session('notification.messege') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="card card-warning card-outline shadow-sm">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">
                    Return Against PO: <strong>#{{ $po->po_number }}</strong>
                </h3>
                <a href="{{ route('po.list') }}" class="btn btn-sm btn-light">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>

            <div class="card-body">

                <!-- PO Info -->
                <div class="row mb-4 p-3 bg-light rounded">
                    <div class="col-md-3"><strong>Supplier:</strong><br>{{ $po->supplier->name ?? 'N/A' }}</div>
                    <div class="col-md-3"><strong>Order Date:</strong><br>{{ \Carbon\Carbon::parse($po->order_date)->format('d M Y') }}</div>
                    <div class="col-md-3"><strong>PO Total:</strong><br>৳ {{ number_format($po->total_amount, 2) }}</div>
                    <div class="col-md-3"><strong>Status:</strong><br>
                        <span class="badge bg-{{ $po->status == 'completed' ? 'success' : 'warning' }}">{{ ucfirst($po->status) }}</span>
                    </div>
                </div>

                <!-- Return Form -->
                <form action="{{ route('po.return.store') }}" method="POST" id="returnForm">
                    @csrf
                    <input type="hidden" name="purchase_order_id" value="{{ $po->id }}">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="30%">Product</th>
                                    <th width="12%">Purchased</th>
                                    <th width="12%">Returned</th>
                                    <th width="12%">Available</th>
                                    <th width="12%">Unit Price</th>
                                    <th width="10%">Return Qty</th>
                                    <th width="12%">Line Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($po->items as $item)
                                @php
                                $alreadyReturned = $item->returned_quantity;
                                $available = $item->available_to_return;
                                $unitPrice = $item->unit_price;
                                $oldQty = old("products.{$item->product_id}.quantity");
                                @endphp
                                <tr class="{{ $available <= 0 ? 'table-secondary' : '' }}">
                                    <td>
                                        <strong>{{ $item->product->name ?? 'Product Deleted' }}</strong>
                                        @if($item->product && $item->product->sku)
                                        <br><small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-center text-warning">{{ $alreadyReturned }}</td>
                                    <td class="text-center fw-bold {{ $available <= 0 ? 'text-danger' : 'text-success' }}">{{ $available }}</td>
                                    <td class="text-end">৳ {{ number_format($unitPrice, 2) }}</td>
                                    <td>
                                        @if($available > 0)
                                        <input type="number"
                                            name="products[{{ $item->product_id }}][quantity]"
                                            class="form-control form-control-sm return-qty text-center @error('products.'.$item->product_id.'.quantity') is-invalid @enderror"
                                            min="1"
                                            max="{{ $available }}"
                                            value="{{ $oldQty ?? '' }}"
                                            data-price="{{ $unitPrice }}"
                                            placeholder="0">
                                        @error('products.'.$item->product_id.'.quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @else
                                        <span class="badge bg-secondary">Fully Returned</span>
                                        <input type="hidden" name="products[{{ $item->product_id }}][quantity]" value="0">
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold"><span class="row-total">৳ 0.00</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">No items found in this Purchase Order.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer -->
                    <div class="row mt-4 pt-3 border-top">
                        <div class="col-md-6">
                            <label class="form-label"><strong>Return Reason <span class="text-danger">*</span></strong></label>
                            <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="3" placeholder="Brief reason for this return..." required>{{ old('reason') }}</textarea>
                            @error('reason')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-end">
                                    <h5 class="mb-2">Grand Return Total</h5>
                                    <h2 class="text-success fw-bold mb-3">৳ <span id="grand-total">0.00</span></h2>

                                    <button type="submit" class="btn btn-success btn-lg px-4" id="submitBtn">
                                        <i class="fas fa-undo-alt me-2"></i>Submit Return
                                    </button>
                                    <p class="text-muted small mt-2 mb-0">
                                        <i class="fas fa-info-circle"></i> Stock & supplier accounts will update automatically.
                                    </p>
                                </div>
                            </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.return-qty').forEach(input => {
            input.addEventListener('input', function() {
                calculateLineTotal(this);
                calculateGrandTotal();
            });
            if (input.value) calculateLineTotal(input);
        });
        calculateGrandTotal();
    });

    function calculateLineTotal(input) {
        const qty = parseFloat(input.value) || 0;
        const price = parseFloat(input.dataset.price) || 0;
        const total = qty * price;
        input.closest('tr').querySelector('.row-total').textContent = '৳ ' + total.toFixed(2);
        return total;
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.return-qty').forEach(input => {
            if (input.value && !input.disabled && input.value > 0) {
                grandTotal += calculateLineTotal(input);
            }
        });
        document.getElementById('grand-total').textContent = grandTotal.toFixed(2);

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = grandTotal <= 0;
        submitBtn.classList.toggle('btn-secondary', grandTotal <= 0);
        submitBtn.classList.toggle('btn-success', grandTotal > 0);
    }

    document.getElementById('returnForm').addEventListener('submit', function(e) {
        const total = document.getElementById('grand-total').textContent;
        if (parseFloat(total) <= 0) {
            e.preventDefault();
            alert('Please enter return quantity for at least one product.');
            return false;
        }
        return confirm(`Confirm return of ৳ ${total}? This will update stock and supplier accounts.`);
    });
</script>
@endsection