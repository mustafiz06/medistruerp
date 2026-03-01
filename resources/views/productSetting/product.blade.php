@extends('layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-box mr-2"></i>{{ __('Product Management') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i> {{ __('Home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Products') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        {{-- Search & Filter Section --}}
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-filter mr-2"></i>{{ __('Search & Filters') }}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('product.index') }}" method="GET" class="form-inline">
                            <div class="form-group mr-2 mb-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    </div>
                                    <input type="text" name="search" class="form-control" placeholder="Search by name..." value="{{ request('search') }}">
                                </div>
                            </div>

                            <div class="form-group mr-2 mb-2">
                                <select name="category_id" class="form-control">
                                    <option value="">All Categories</option>
                                    @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->title }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mr-2 mb-2">
                                <select name="brand_id" class="form-control">
                                    <option value="">All Brands</option>
                                    @foreach($brands ?? [] as $brand)
                                    <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->title }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary mr-2 mb-2">
                                <i class="fas fa-search"></i> {{ __('Filter') }}
                            </button>
                            <a href="{{ route('product.index') }}" class="btn btn-secondary mb-2">
                                <i class="fas fa-redo"></i> {{ __('Reset') }}
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Product List Table --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-list mr-2"></i>{{ __('Product List') }}
                                @if(method_exists($products, 'total'))
                                <span class="badge badge-primary ml-2">{{ $products->total() }} {{ __('Products') }}</span>
                                @endif
                            </h3>
                            <a href="{{ route('product.add') }}" class="btn btn-success btn-sm ml-auto">
                                <i class="fas fa-plus"></i> {{ __('Add Product') }}
                            </a>
                        </div>
                    </div>

                    <div class="card-body table-responsive p-0">
                        @if($products->count() > 0)
                        <table class="table table-hover table-bordered table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 50px;" class="text-center">#</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Brand') }}</th>
                                    <th>{{ __('Unit') }}</th>
                                    <th>{{ __('Origin') }}</th>
                                    <th class="text-center">{{ __('Alert Qty') }}</th>
                                    <th class="text-right">{{ __('Purchase Price') }}</th>
                                    <th class="text-right">{{ __('Sales Price') }}</th>
                                    <th class="text-center">{{ __('Status') }}</th>
                                    <th class="text-center" style="width: 180px;">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                <tr>
                                    <td class="text-center">
                                        @if(method_exists($products, 'firstItem'))
                                        {{ $products->firstItem() + $loop->index }}
                                        @else
                                        {{ $loop->iteration }}
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $product->name }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $product->category->title ?? '-' }}</span>
                                    </td>
                                    <td>{{ $product->brand->title ?? '-' }}</td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $product->unit->title ?? '-' }}</span>
                                    </td>
                                    <td>{{ $product->origin->title ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($product->alert_quantity > 0)
                                        <span class="badge badge-warning">{{ number_format($product->alert_quantity) }}</span>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <strong>{{ number_format($product->purchase_price, 2) }}</strong>
                                    </td>
                                    <td class="text-right">
                                        <strong class="text-success">{{ number_format($product->sales_price, 2) }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $product->is_active == 1 ? 'badge-success' : 'badge-danger' }}">
                                            {{ $product->is_active == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('product.barcode.generate', $product->id) }}"
                                                target="_blank"
                                                class="btn btn-primary btn-sm"
                                                title="Print Barcode">
                                                <i class="fas fa-barcode"></i>
                                            </a>
                                            <a href="{{ route('product.edit.view', $product->id) }}"
                                                class="btn btn-info btn-sm"
                                                title="Edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <form action="{{ route('product.delete', $product->id) }}"
                                                method="POST"
                                                class="d-inline delete-form">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-danger btn-sm"
                                                    title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('No products found') }}</h5>
                            <a href="{{ route('product.add') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-plus"></i> {{ __('Add Your First Product') }}
                            </a>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

    </div>
</section>
@endsection