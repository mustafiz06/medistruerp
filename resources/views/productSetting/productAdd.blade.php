@extends('layout')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0 text-dark">Add Product</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">

            <div class="card-header">
                <h3 class="card-title">Product Information</h3>
            </div>

            <div class="card-body">
                <form action="{{ route('product.store') }}" method="POST">
                    @csrf

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Name <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">SKU</label>
                        <div class="col-sm-10">
                            <input type="text" name="sku" class="form-control" value="{{ old('sku') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-10">
                            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Category</label>
                        <div class="col-sm-10">
                            <select name="category_id" class="form-control">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Brand</label>
                        <div class="col-sm-10">
                            <select name="brand_id" class="form-control">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Unit</label>
                        <div class="col-sm-10">
                            <select name="unit_id" class="form-control">
                                <option value="">Select Unit</option>
                                @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Origin</label>
                        <div class="col-sm-10">
                            <select name="origin_id" class="form-control">
                                <option value="">Select Origin</option>
                                @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Purchase Price</label>
                        <div class="col-sm-10">
                            <input type="number" step="0.01" name="purchase_price" class="form-control" value="{{ old('purchase_price') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Sales Price</label>
                        <div class="col-sm-10">
                            <input type="number" step="0.01" name="sales_price" class="form-control" value="{{ old('sales_price') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Alert Quantity</label>
                        <div class="col-sm-10">
                            <input type="number" name="alert_quantity" class="form-control" value="{{ old('alert_quantity', 0) }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Save Product</button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</section>

@endsection