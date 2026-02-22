@extends('layout')

@section('content')
<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">

                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h3 class="card-title mb-0">Product List</h3>

                            <a href="{{ route('product.add') }}"
                                class="btn btn-success btn-sm ml-auto">
                                <i class="fas fa-plus"></i> Add Product
                            </a>
                        </div>
                    </div>


                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-striped data_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Unit</th>
                                    <th>Origin</th>
                                    <th>Alert Stock Qty</th>
                                    <th>Purchase Price</th>
                                    <th>Sales Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->title ?? '-' }}</td>
                                    <td>{{ $product->brand->title ?? '-' }}</td>
                                    <td>{{ $product->unit->title ?? '-' }}</td>
                                    <td>{{ $product->origin->title ?? '-' }}</td>
                                    <td>{{ number_format($product->alert_quantity, 2) }}</td>
                                    <td>{{ number_format($product->purchase_price, 2) }}</td>
                                    <td>{{ number_format($product->sales_price, 2) }}</td>
                                    <td>
                                        <a href="{{ route('product.barcode.generate', $product->id) }}"
                                            target="_blank"
                                            class="btn btn-primary btn-sm"> <i class="fas fa-barcode"></i>
                                            Print
                                        </a>
                                        <a href="{{ route('product.edit.view', $product->id) }}"
                                            class="btn btn-info btn-sm">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </a>
                                        <form id="deleteform" class="d-inline-block" action="{{ route('product.delete', $product->id ) }}" method="post">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $product->id }}">
                                            <button type="submit" class="btn btn-danger btn-sm" id="delete" disabled>
                                                <i class="fas fa-trash"></i>{{ __('Delete') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
</section>
@endsection