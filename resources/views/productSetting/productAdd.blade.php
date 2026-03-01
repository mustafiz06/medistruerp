@extends('layout')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Add Product') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('product.index') }}">{{ __('Products') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Add New') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        
        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h5><i class="icon fas fa-ban"></i> Validation Error!</h5>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('product.store') }}" method="POST" id="productForm">
            @csrf
            
            <div class="row">
                {{-- Left Column - Product Info --}}
                <div class="col-md-8">
                    {{-- Basic Information --}}
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-box mr-2"></i>{{ __('Basic Information') }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">{{ __('Product Name') }} <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" 
                                       placeholder="Enter product name" 
                                       required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">{{ __('Description') }}</label>
                                <textarea name="description" 
                                          id="description" 
                                          class="form-control @error('description') is-invalid @enderror" 
                                          rows="4" 
                                          placeholder="Enter product description">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Pricing Information --}}
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-tag mr-2"></i>{{ __('Pricing Information') }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="purchase_price">{{ __('Purchase Price') }}</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">৳</span>
                                            </div>
                                            <input type="number" 
                                                   step="0.01" 
                                                   min="0" 
                                                   name="purchase_price" 
                                                   id="purchase_price" 
                                                   class="form-control @error('purchase_price') is-invalid @enderror" 
                                                   value="{{ old('purchase_price', 0) }}" 
                                                   placeholder="0.00">
                                        </div>
                                        @error('purchase_price')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sales_price">{{ __('Sales Price') }} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">৳</span>
                                            </div>
                                            <input type="number" 
                                                   step="0.01" 
                                                   min="0" 
                                                   name="sales_price" 
                                                   id="sales_price" 
                                                   class="form-control @error('sales_price') is-invalid @enderror" 
                                                   value="{{ old('sales_price') }}" 
                                                   placeholder="0.00" 
                                                   required>
                                        </div>
                                        @error('sales_price')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="alert_quantity">{{ __('Alert Quantity') }}</label>
                                        <input type="number" 
                                               min="0" 
                                               name="alert_quantity" 
                                               id="alert_quantity" 
                                               class="form-control @error('alert_quantity') is-invalid @enderror" 
                                               value="{{ old('alert_quantity', 0) }}" 
                                               placeholder="0">
                                        <small class="text-muted"><i class="fas fa-info-circle"></i> Low stock alert</small>
                                        @error('alert_quantity')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column - Classification --}}
                <div class="col-md-4">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list mr-2"></i>{{ __('Classification') }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="category_id">{{ __('Category') }}</label>
                                <select name="category_id" 
                                        id="category_id" 
                                        class="form-control @error('category_id') is-invalid @enderror">
                                    <option value="">{{ __('-- Select Category --') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="brand_id">{{ __('Brand') }}</label>
                                <select name="brand_id" 
                                        id="brand_id" 
                                        class="form-control @error('brand_id') is-invalid @enderror">
                                    <option value="">{{ __('-- Select Brand --') }}</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="unit_id">{{ __('Unit') }}</label>
                                <select name="unit_id" 
                                        id="unit_id" 
                                        class="form-control @error('unit_id') is-invalid @enderror">
                                    <option value="">{{ __('-- Select Unit --') }}</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('unit_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="origin_id">{{ __('Country of Origin') }}</label>
                                <select name="origin_id" 
                                        id="origin_id" 
                                        class="form-control @error('origin_id') is-invalid @enderror">
                                    <option value="">{{ __('-- Select Country --') }}</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ old('origin_id') == $country->id ? 'selected' : '' }}>
                                            {{ $country->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('origin_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary btn-block btn-lg">
                                <i class="fas fa-save mr-2"></i>{{ __('Save Product') }}
                            </button>
                            <a href="{{ route('product.index') }}" class="btn btn-secondary btn-block mt-2">
                                <i class="fas fa-times mr-2"></i>{{ __('Cancel') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>


@endsection