@extends('layout')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">
                        <i class="fas fa-plus-circle mr-2"></i>{{ __('New Sale') }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">{{ __('Sales') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('New Sale') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            
            {{-- Alerts --}}
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
            @endif

            <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
                @csrf
                
                <div class="row">
                    {{-- LEFT: Product Search & Cart --}}
                    <div class="col-12 col-lg-8">
                        
                        {{-- Product Search Card --}}
                        <div class="card card-primary card-outline mb-3">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-search mr-2"></i>{{ __('Search Product') }}
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="input-group input-group-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="fas fa-search"></i>
                                        </span>
                                    </div>
                                    <input type="text" id="productSearch" class="form-control" 
                                           placeholder="{{ __('Type product name or SKU...') }}"
                                           autocomplete="off" autofocus>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary" id="searchBtn">
                                            <i class="fas fa-search"></i> <span class="d-none d-sm-inline">{{ __('Search') }}</span>
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-1">
                                    <i class="fas fa-info-circle"></i> 
                                    {{ __('Enter at least 2 characters to search products') }}
                                </small>
                                
                                {{-- Search Results --}}
                                <div id="searchResults" class="mt-2" style="max-height: 300px; overflow-y: auto;"></div>
                            </div>
                        </div>

                        {{-- Cart Card --}}
                        <div class="card card-success card-outline">
                            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                                <h3 class="card-title mb-0">
                                    <i class="fas fa-shopping-cart mr-2"></i>{{ __('Cart Items') }}
                                    <span class="badge badge-primary ml-2" id="cartCount">0</span>
                                </h3>
                                <button type="button" class="btn btn-danger btn-sm" id="clearCartBtn">
                                    <i class="fas fa-trash"></i> <span class="d-none d-sm-inline">{{ __('Clear') }}</span>
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm mb-0" id="cartTable">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="text-center" style="width:40px;">#</th>
                                                <th>{{ __('Product') }}</th>
                                                <th class="text-center d-none d-md-table-cell" style="width:80px;">{{ __('Stock') }}</th>
                                                <th style="width:90px;">{{ __('Qty') }}</th>
                                                <th class="text-right" style="width:100px;">{{ __('Price') }}</th>
                                                <th class="text-right d-none d-sm-table-cell" style="width:80px;">{{ __('Disc') }}</th>
                                                <th class="text-right" style="width:100px;">{{ __('Total') }}</th>
                                                <th class="text-center" style="width:50px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="cartBody">
                                            {{-- Cart items added dynamically --}}
                                        </tbody>
                                    </table>
                                </div>
                                <div id="emptyCart" class="text-center py-5">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('Cart is empty') }}</h5>
                                    <p class="text-muted small">{{ __('Search and select products to add to cart') }}</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- RIGHT: Customer, PO, Payment --}}
                    <div class="col-12 col-lg-4">
                        
                        {{-- Customer & PO Card --}}
                        <div class="card card-info card-outline mb-3">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-user mr-2"></i>{{ __('Customer & PO') }}
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>{{ __('Customer') }}</label>
                                    <select name="customer_id" class="form-control select2" style="width:100%;">
                                        <option value="">{{ __('Walk-in Customer') }}</option>
                                        @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" 
                                                data-address="{{ $customer->address ?? '' }}">
                                            {{ $customer->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Customer PO Number') }}</label>
                                    <input type="text" name="po_number" class="form-control" 
                                           placeholder="{{ __('PO-2024-001') }}" value="{{ old('po_number') }}">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('PO Date') }}</label>
                                    <input type="date" name="po_date" class="form-control" value="{{ old('po_date') }}">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Sale Date') }} <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="sale_date" class="form-control" 
                                           value="{{ old('sale_date', date('Y-m-d\TH:i')) }}" required>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Shipping Address') }}</label>
                                    <textarea name="shipping_address" id="shippingAddress" class="form-control" rows="2" placeholder="{{ __('Optional') }}"></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Summary Card --}}
                        <div class="card card-warning card-outline mb-3">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-calculator mr-2"></i>{{ __('Summary') }}
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>{{ __('Subtotal') }}</span>
                                    <span id="subtotalDisplay">$0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>{{ __('Tax') }} (<span id="taxRateDisplay">0</span>%)</span>
                                    <span id="taxDisplay">$0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>{{ __('Discount') }}</span>
                                    <span id="discountDisplay">$0.00</span>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between">
                                    <span class="font-weight-bold text-lg">{{ __('Grand Total') }}</span>
                                    <span class="font-weight-bold text-lg text-success" id="grandTotalDisplay">$0.00</span>
                                </div>
                                <div class="form-group mt-3">
                                    <label class="small">{{ __('Tax Rate') }}</label>
                                    <select name="tax_rate" id="taxRate" class="form-control form-control-sm">
                                        <option value="0">0%</option>
                                        <option value="5">5%</option>
                                        <option value="10">10%</option>
                                        <option value="15">15%</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="small">{{ __('Additional Discount') }}</label>
                                    <input type="number" name="discount_amount" id="discountAmount" 
                                           class="form-control form-control-sm" value="0" min="0" step="0.01">
                                </div>
                            </div>
                        </div>

                        {{-- Payment Card --}}
                        <div class="card card-primary card-outline mb-3">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-credit-card mr-2"></i>{{ __('Payment') }}
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>{{ __('Paid Amount') }}</label>
                                    <input type="number" name="paid_amount" id="paidAmount" 
                                           class="form-control" value="0" min="0" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Payment Method') }}</label>
                                    <select name="payment_method" class="form-control">
                                        <option value="cash">{{ __('Cash') }}</option>
                                        <option value="bank_transfer">{{ __('Bank Transfer') }}</option>
                                        <option value="card">{{ __('Card') }}</option>
                                        <option value="online">{{ __('Online') }}</option>
                                        <option value="due">{{ __('Due / Credit') }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Reference') }}</label>
                                    <input type="text" name="payment_reference" class="form-control" 
                                           placeholder="{{ __('Txn ID, Cheque No...') }}">
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span class="font-weight-bold">{{ __('Due') }}</span>
                                    <span class="font-weight-bold text-danger" id="dueDisplay">$0.00</span>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Card --}}
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>{{ __('Notes') }}</label>
                                    <textarea name="notes" class="form-control" rows="2" 
                                              placeholder="{{ __('Additional notes...') }}">{{ old('notes') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-success btn-block btn-lg" id="completeSaleBtn" disabled>
                                    <i class="fas fa-check-circle mr-2"></i>{{ __('Complete Sale') }} 
                                    <span class="badge badge-light" id="btnTotal">$0.00</span>
                                </button>
                                <a href="{{ route('sales.index') }}" class="btn btn-secondary btn-block mt-2">
                                    <i class="fas fa-times mr-2"></i>{{ __('Cancel') }}
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

{{-- Hidden inputs for cart items --}}
<div id="cartItemsContainer"></div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<style>
    /* Responsive fixes */
    @media (max-width: 768px) {
        .card-header .card-title { font-size: 1rem; }
        .input-group-lg .form-control { font-size: 1rem; }
        #cartTable th, #cartTable td { padding: 0.25rem !important; font-size: 0.85rem; }
    }
    
    /* Search results styling */
    #searchResults .list-group-item {
        cursor: pointer;
        padding: 0.75rem 1rem;
        border-left: 3px solid transparent;
        transition: all 0.2s;
    }
    #searchResults .list-group-item:hover {
        background: #f8f9fa;
        border-left-color: #007bff;
    }
    #searchResults .list-group-item.active {
        background: #e7f1ff;
        border-left-color: #007bff;
    }
    #searchResults .stock-badge {
        font-size: 0.75rem;
    }
    #searchResults .price-tag {
        font-weight: 600;
        color: #28a745;
    }
    .opacity-50 { opacity: 0.5; pointer-events: none; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    let cart = [];
    let searchTimeout;

    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        dropdownParent: $('#saleForm')
    });

    // Auto-fill shipping address when customer selected
    $('select[name="customer_id"]').on('change', function() {
        const address = $(this).find('option:selected').data('address');
        if (address) $('#shippingAddress').val(address);
    });

    // ===== PRODUCT SEARCH =====
    
    // Live search with debounce
    $('#productSearch').on('input', function() {
        clearTimeout(searchTimeout);
        const value = $(this).val().trim();
        
        if (value.length >= 2) {
            searchTimeout = setTimeout(() => {
                searchProducts(value);
            }, 300);
        } else {
            $('#searchResults').html('');
        }
    });

    // Search button click
    $('#searchBtn').on('click', function() {
        const value = $('#productSearch').val().trim();
        if (value.length >= 2) {
            searchProducts(value);
        }
    });

    // Search products via AJAX - USING DIRECT URL
    function searchProducts(query) {
        $('#searchResults').html('<div class="text-center py-2"><i class="fas fa-spinner fa-spin"></i> Searching...</div>');
        
        $.ajax({
            //  Direct URL - no route name issues
            url: '/api/products/search',
            method: 'GET',
             { 
                q: query,
                limit: 10
            },
            success: function(response) {
                console.log('Search response:', response);
                
                if (response.success && response.data && response.data.length > 0) {
                    displaySearchResults(response.data);
                } else {
                    $('#searchResults').html(`
                        <div class="alert alert-warning alert-sm py-1 mb-0">
                            <i class="fas fa-info-circle"></i> No products found for "${query}"
                        </div>
                    `);
                }
            },
            error: function(xhr, status, error) {
                console.error('Search error:', error, xhr.responseText);
                $('#searchResults').html(`
                    <div class="alert alert-danger alert-sm py-1 mb-0">
                        <i class="fas fa-exclamation-circle"></i> Search failed: ${error || 'Unknown error'}
                    </div>
                `);
            }
        });
    }

    // Display search results
    function displaySearchResults(products) {
        if (!products || products.length === 0) {
            $('#searchResults').html('<div class="alert alert-warning">No results</div>');
            return;
        }
        
        let html = '<div class="list-group">';
        
        products.forEach(product => {
            if (!product.id) return;
            
            const available = parseInt(product.available) || 0;
            const stockClass = available <= 0 ? 'text-danger' : 'text-success';
            const stockText = available <= 0 ? 'Out of Stock' : `${available} in stock`;
            const disabled = available <= 0 ? 'disabled opacity-50' : '';
            
            // Escape for data attribute
            const productJson = JSON.stringify(product).replace(/'/g, "&apos;");
            
            html += `
                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center ${disabled}" 
                   data-product='${productJson}' ${disabled}>
                    <div class="flex-grow-1">
                        <strong>${escapeHtml(product.name)}</strong>
                        ${product.sku ? `<small class="text-muted d-block">${escapeHtml(product.sku)}</small>` : ''}
                    </div>
                    <div class="text-right ml-3">
                        <span class="font-weight-bold text-success d-block">$${product.price}</span>
                        <small class="${stockClass}">${stockText}</small>
                    </div>
                </a>
            `;
        });
        
        html += '</div>';
        $('#searchResults').html(html);
        
        // Click handler
        $('#searchResults .list-group-item:not(.disabled)').off('click').on('click', function(e) {
            e.preventDefault();
            try {
                const product = JSON.parse($(this).data('product').replace(/&apos;/g, "'"));
                addToCart(product);
                $('#productSearch').val('').focus();
                $('#searchResults').html('');
            } catch(err) {
                console.error('Error parsing product:', err);
            }
        });
    }
    
    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        if (!text) return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    // ===== CART FUNCTIONS =====

    function addToCart(product) {
        console.log('Adding to cart:', product);
        
        const existing = cart.find(item => item.id === product.id);
        const available = parseInt(product.available) || 0;
        
        if (existing) {
            if (existing.quantity + 1 > available) {
                alert('⚠️ Insufficient stock! Only ' + available + ' available');
                return;
            }
            existing.quantity++;
        } else {
            if (available <= 0) {
                alert('⚠️ Out of stock!');
                return;
            }
            cart.push({ 
                id: product.id,
                name: product.name,
                sku: product.sku || '',
                price: parseFloat(product.price) || 0,
                available: available,
                quantity: 1,
                discount: 0
            });
        }
        renderCart();
    }

    function renderCart() {
        if (cart.length === 0) {
            $('#cartBody').html('');
            $('#emptyCart').show();
            $('#cartTable').hide();
            $('#cartCount').text('0');
            $('#completeSaleBtn').prop('disabled', true);
            updateTotals();
            return;
        }

        $('#emptyCart').hide();
        $('#cartTable').show();
        $('#cartCount').text(cart.length);
        $('#completeSaleBtn').prop('disabled', false);

        let html = '', subtotal = 0;
        
        cart.forEach((item, index) => {
            const lineTotal = (item.price * item.quantity) - (item.discount || 0);
            subtotal += item.price * item.quantity;
            
            html += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>
                        <strong>${escapeHtml(item.name)}</strong>
                        ${item.sku ? `<small class="text-muted d-block">${escapeHtml(item.sku)}</small>` : ''}
                    </td>
                    <td class="text-center d-none d-md-table-cell ${item.available <= 0 ? 'text-danger' : 'text-muted'}">
                        ${item.available}
                    </td>
                    <td>
                        <input type="number" name="items[${index}][quantity]" 
                               class="form-control form-control-sm qty-input" 
                               value="${item.quantity}" min="1" max="${item.available}" 
                               data-index="${index}">
                    </td>
                    <td class="text-right">
                        <input type="number" name="items[${index}][unit_price]" 
                               class="form-control form-control-sm price-input text-right" 
                               value="${item.price.toFixed(2)}" min="0" step="0.01" 
                               data-index="${index}">
                    </td>
                    <td class="text-right d-none d-sm-table-cell">
                        <input type="number" name="items[${index}][discount]" 
                               class="form-control form-control-sm disc-input text-right" 
                               value="${item.discount || 0}" min="0" step="0.01" 
                               data-index="${index}">
                    </td>
                    <td class="text-right"><strong>$${lineTotal.toFixed(2)}</strong></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-item" data-index="${index}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });

        $('#cartBody').html(html);
        updateCartHiddenInputs();
        updateTotals();
    }

    function updateCartHiddenInputs() {
        let html = '';
        cart.forEach((item, index) => {
            html += `
                <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                <input type="hidden" name="items[${index}][unit_price]" value="${item.price}">
                <input type="hidden" name="items[${index}][discount]" value="${item.discount || 0}">
            `;
        });
        $('#cartItemsContainer').html(html);
    }

    // Remove item from cart
    $(document).on('click', '.remove-item', function() {
        const index = $(this).data('index');
        cart.splice(index, 1);
        renderCart();
    });

    // Update cart on input change
    $(document).on('input', '.qty-input, .price-input, .disc-input', function() {
        const index = $(this).data('index');
        const field = $(this).attr('name').split('[')[1].split(']')[0];
        const value = parseFloat($(this).val()) || 0;
        
        if (field === 'quantity') {
            const available = cart[index].available;
            if (value > available) {
                alert('⚠️ Cannot exceed available stock: ' + available);
                $(this).val(available);
                cart[index][field] = available;
            } else {
                cart[index][field] = value;
            }
        } else {
            cart[index][field] = value;
        }
        renderCart();
    });

    // Update totals
    function updateTotals() {
        let subtotal = 0, itemDiscount = 0;
        
        cart.forEach(item => {
            subtotal += item.price * item.quantity;
            itemDiscount += item.discount || 0;
        });

        const taxRate = parseFloat($('#taxRate').val()) || 0;
        const addDiscount = parseFloat($('#discountAmount').val()) || 0;
        const taxableAmount = Math.max(0, subtotal - itemDiscount - addDiscount);
        const tax = taxableAmount * (taxRate / 100);
        const grandTotal = Math.max(0, taxableAmount + tax);
        const paid = parseFloat($('#paidAmount').val()) || 0;
        const due = Math.max(0, grandTotal - paid);
        const change = Math.max(0, paid - grandTotal);

        $('#subtotalDisplay').text('$' + subtotal.toFixed(2));
        $('#taxRateDisplay').text(taxRate);
        $('#taxDisplay').text('$' + tax.toFixed(2));
        $('#discountDisplay').text('$' + (itemDiscount + addDiscount).toFixed(2));
        $('#grandTotalDisplay').text('$' + grandTotal.toFixed(2));
        $('#dueDisplay').text('$' + due.toFixed(2));
        $('#btnTotal').text('$' + grandTotal.toFixed(2));

        // Auto-fill paid amount if empty and grand total changed
        if (paid === 0 && grandTotal > 0) {
            $('#paidAmount').val(grandTotal.toFixed(2));
        }
    }

    // Recalculate on changes
    $('#taxRate, #discountAmount, #paidAmount').on('input change', updateTotals);

    // Clear cart
    $('#clearCartBtn').on('click', function() {
        if (cart.length === 0) return;
        if (confirm('Clear all items from cart?')) {
            cart = [];
            renderCart();
            $('#productSearch').val('').focus();
        }
    });

    // Form submission
    $('#saleForm').on('submit', function(e) {
        if (cart.length === 0) {
            e.preventDefault();
            alert('⚠️ Please add items to cart!');
            return false;
        }
        
        const grandTotal = parseFloat($('#grandTotalDisplay').text().replace('$', '')) || 0;
        const paid = parseFloat($('#paidAmount').val()) || 0;
        
        if (paid < grandTotal * 0.1 && grandTotal > 0) {
            if (!confirm('Payment is less than 10% of total. Continue with due amount?')) {
                e.preventDefault();
                return false;
            }
        }
        
        $('#completeSaleBtn').html('<i class="fas fa-spinner fa-spin mr-2"></i>Processing...')
            .prop('disabled', true);
    });

    // Close search results when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#productSearch, #searchResults, #searchBtn').length) {
            $('#searchResults').html('');
        }
        if (!$(e.target).closest('input, select, button, .table, .list-group').length) {
            $('#productSearch').focus();
        }
    });

    // Focus on load
    $('#productSearch').focus();
    
    // Debug: Log that script is loaded
    console.log(' Sales create script loaded');
});
</script>
@endpush
@endsection