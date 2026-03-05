@extends('layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-chart-bar mr-2"></i>{{ __('Stock Report') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i> {{ __('Home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Stock Report') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        {{-- Summary Cards --}}
        <div class="row mb-3">
            <div class="col-md-3 col-sm-6">
                <div class="info-box">
                    <span class="info-box-icon bg-primary"><i class="fas fa-boxes"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ __('Total Products') }}</span>
                        <span class="info-box-number">{{ number_format($stats->total_products ?? 0) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-dollar-sign"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ __('Total Value') }}</span>
                        <span class="info-box-number">${{ number_format($stats->total_value ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ __('Low Stock') }}</span>
                        <span class="info-box-number">{{ number_format($stats->low_stock_count ?? 0) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-danger">
                    <span class="info-box-icon"><i class="fas fa-times-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ __('Out of Stock') }}</span>
                        <span class="info-box-number">{{ number_format($stats->out_of_stock_count ?? 0) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stock Alerts --}}
        @if(($stats->low_stock_count ?? 0) > 0 || ($stats->out_of_stock_count ?? 0) > 0)
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> {{ __('Stock Alert!') }}</h5>
                    @if(($stats->out_of_stock_count ?? 0) > 0)
                    <strong>{{ $stats->out_of_stock_count }} {{ __('products are out of stock.') }}</strong>
                    @endif
                    @if(($stats->low_stock_count ?? 0) > 0)
                    <strong>{{ $stats->low_stock_count }} {{ __('products are low on stock.') }}</strong>
                    @endif
                    <a href="{{ route('stock.report', ['low_stock_only' => 1]) }}" class="alert-link ml-2">{{ __('View Details') }}</a>
                </div>
            </div>
        </div>
        @endif

        {{-- Search & Filter Section --}}
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-filter mr-2"></i>{{ __('Search & Filters') }}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('stock.report') }}" method="GET" class="form-inline">
                            <div class="form-group mr-2 mb-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    </div>
                                    <input type="text" name="search" class="form-control" placeholder="Search by name or SKU..." value="{{ request('search') }}">
                                </div>
                            </div>

                            

                            <div class="form-group mr-2 mb-2">
                                <select name="stock_status" class="form-control">
                                    <option value="">{{ __('All Status') }}</option>
                                    <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>{{ __('In Stock') }}</option>
                                    <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>{{ __('Low Stock') }}</option>
                                    <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>{{ __('Out of Stock') }}</option>
                                </select>
                            </div>

                            <div class="form-group mr-2 mb-2">
                                <select name="low_stock_only" class="form-control">
                                    <option value="0" {{ !request('low_stock_only') ? 'selected' : '' }}>{{ __('Show All') }}</option>
                                    <option value="1" {{ request('low_stock_only') ? 'selected' : '' }}>{{ __('Low Stock Only') }}</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary mr-2 mb-2">
                                <i class="fas fa-search"></i> {{ __('Filter') }}
                            </button>
                            <a href="{{ route('stock.report') }}" class="btn btn-secondary mb-2">
                                <i class="fas fa-redo"></i> {{ __('Reset') }}
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stock List Table --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-list mr-2"></i>{{ __('Stock List') }}
                                @if(method_exists($inventories, 'total'))
                                <span class="badge badge-primary ml-2">{{ $inventories->total() }} {{ __('Products') }}</span>
                                @endif
                            </h3>
                            <a class="ml-auto">
                                
                            </a>
                            <a href="{{ route('stock.reportExport') }}" class="btn btn-info btn-sm ml-2">
                                <i class="fas fa-download"></i> {{ __('Export CSV') }}
                            </a>
                        </div>
                    </div>

                    <div class="card-body table-responsive p-0">
                        @if($inventories->count() > 0)
                        <table class="table table-hover table-bordered table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 50px;" class="text-center">#</th>
                                    <th>{{ __('SKU') }}</th>
                                    <th>{{ __('Product Name') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th class="text-center">{{ __('Current Stock') }}</th>
                                    <th class="text-center">{{ __('Available') }}</th>
                                    <th class="text-center">{{ __('Reserved') }}</th>
                                    <th class="text-right">{{ __('Unit Cost') }}</th>
                                    <th class="text-right">{{ __('Stock Value') }}</th>
                                    <th class="text-center">{{ __('Status') }}</th>
                                    <th class="text-center" style="width: 120px;">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inventories as $inventory)
                                @php
                                $product = $inventory->product;
                                $currentStock = $inventory->current_stock ?? 0;
                                $availableStock = $inventory->available_stock ?? 0;
                                $reservedStock = $inventory->reserved_stock ?? 0;
                                $stockValue = $inventory->current_stock_value ?? 0;
                                $unitCost = $currentStock > 0 ? ($stockValue / $currentStock) : 0;
                                $status = $inventory->stock_status ?? 'in_stock';

                                // Row class based on status
                                $rowClass = '';
                                $badgeClass = 'success';
                                $statusLabel = __('In Stock');

                                if ($status === 'out_of_stock') {
                                $rowClass = 'table-danger';
                                $badgeClass = 'danger';
                                $statusLabel = __('Out of Stock');
                                } elseif ($status === 'low_stock') {
                                $rowClass = 'table-warning';
                                $badgeClass = 'warning';
                                $statusLabel = __('Low Stock');
                                }
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    <td class="text-center">
                                        @if(method_exists($inventories, 'firstItem'))
                                        {{ $inventories->firstItem() + $loop->index }}
                                        @else
                                        {{ $loop->iteration }}
                                        @endif
                                    </td>
                                    <td><code>{{ $product->sku ?? '-' }}</code></td>
                                    <td>
                                        <strong>{{ $product->name ?? '-' }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $product->category->title ?? '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <strong>{{ number_format($currentStock, 2) }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-primary">{{ number_format($availableStock, 2) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-muted">{{ number_format($reservedStock, 2) }}</span>
                                    </td>
                                    <td class="text-right">
                                        <strong>${{ number_format($unitCost, 2) }}</strong>
                                    </td>
                                    <td class="text-right">
                                        <strong class="text-success">${{ number_format($stockValue, 2) }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ $badgeClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href=""
                                                class="btn btn-secondary btn-sm"
                                                title="{{ __('History') }}">
                                                <i class="fas fa-history"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('No stock records found') }}</h5>
                            <a href="{{ route('stock.openingForm') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-plus"></i> {{ __('Set Opening Stock') }}
                            </a>
                        </div>
                        @endif
                    </div>

                    @if(method_exists($inventories, 'links'))
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">
                                {{ __('Showing') }} {{ $inventories->firstItem() ?? 0 }} {{ __('to') }} {{ $inventories->lastItem() ?? 0 }} {{ __('of') }} {{ $inventories->total() }} {{ __('products') }}
                            </span>
                            <div>
                                {{ $inventories->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</section>
@endsection