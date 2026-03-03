@extends('layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0 text-dark">Create Purchase Order</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Purchase Order Information</h3>
            </div>

            <div class="card-body">
                <form action="{{ route('po.store') }}" method="POST" id="po-form">
                    @csrf

                    {{-- PO Number --}}
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">PO Number</label>
                        <div class="col-sm-10">
                            <input type="text" name="po_number" class="form-control" value="{{ $poNumber }}" readonly>
                        </div>
                    </div>

                    {{-- Supplier --}}
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Supplier</label>
                        <div class="col-sm-10">
                            <select name="supplier_id" class="form-control" required>
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Date --}}
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Order Date</label>
                        <div class="col-sm-10">
                            <input type="date" name="order_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <hr>
                    <h5>Products</h5>

                    <table class="table table-bordered" id="products-table">
                        <thead>
                            <tr>
                                <th width="30%">Product</th>
                                <th width="15%">Qty</th>
                                <th width="20%">Unit Price</th>
                                <th width="20%">Total</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="products[0][id]" class="form-control product-select" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->purchase_price }}">
                                            {{ $product->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="products[0][quantity]" class="form-control qty" value="1" min="1">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="products[0][unit_price]" class="form-control price" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control row-total" readonly>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-row">Remove</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <button type="button" class="btn btn-success btn-sm mt-2" id="add-product">+ Add Product</button>

                    <hr>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><strong>Grand Total</strong></label>
                        <div class="col-sm-10">
                            <input type="text" id="grand-total" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Create Purchase Order</button>
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
    const productsData = @json($products);
    let rowIndex = 1;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#add-product').click(function() {
        let firstRow = $('#products-table tbody tr:first').clone(true);
        
        firstRow.find('.product-select').val('').prop('selectedIndex', 0);
        firstRow.find('.qty').val(1);
        firstRow.find('.price').val('');
        firstRow.find('.row-total').val('');
        
        firstRow.html(firstRow.html().replace(/\[0\]/g, `[${rowIndex}]`));
        
        $('#products-table tbody').append(firstRow);
        rowIndex++;
    });

    $(document).on('click', '.remove-row', function() {
        if ($('#products-table tbody tr').length > 1) {
            $(this).closest('tr').remove();
            calculateGrandTotal();
        } else {
            alert('At least one product is required.');
        }
    });

    $(document).on('change', '.product-select', function() {
        let row = $(this).closest('tr');
        let productId = $(this).val();
        let selectedOption = $(this).find('option:selected');
        let price = selectedOption.data('price');

        if (!productId) {
            row.find('.price').val('');
            row.find('.row-total').val('');
            calculateGrandTotal();
            return;
        }

        if (price) {
            row.find('.price').val(parseFloat(price).toFixed(2));
            calculateRowTotal(row);
        } else {
            let url = "{{ route('api.products.info', ['id' => '__id__']) }}".replace('__id__', productId);
            $.get(url, function(response) {
                if (response && response.price) {
                    row.find('.price').val(parseFloat(response.price).toFixed(2));
                    calculateRowTotal(row);
                }
            }).fail(function() {
                alert('Error loading product price.');
                row.find('.product-select').val('');
            });
        }
    });

    $(document).on('keyup change', '.qty, .price', function() {
        let row = $(this).closest('tr');
        calculateRowTotal(row);
    });

    function calculateRowTotal(row) {
        let qty = parseFloat(row.find('.qty').val()) || 0;
        let price = parseFloat(row.find('.price').val()) || 0;
        let total = qty * price;
        row.find('.row-total').val(total.toFixed(2));
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        $('.row-total').each(function() {
            grandTotal += parseFloat($(this).val()) || 0;
        });
        $('#grand-total').val(grandTotal.toFixed(2));
    }

    $(document).ready(function() {
        let firstProduct = $('.product-select:first').val();
        if (firstProduct) {
            $('.product-select:first').trigger('change');
        }
    });
</script>
@endsection