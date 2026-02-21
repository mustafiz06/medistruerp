@extends('layout')
@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center">
        <h4>Opening Stock</h4>
        <p>
            <a href="#" class="btn btn-outline-dark btn-sm me-2">
                <i class="fas fa-file-export me-1"></i>Export CSV
            </a>
            <label class="btn btn-outline-dark btn-sm mb-0">
                <i class="fas fa-file-import me-1"></i> Import CSV
                <form action="#" method="POST" enctype="multipart/form-data" class="d-inline">
                    @csrf
                    <input type="file" name="csv_file" class="d-none" onchange="this.form.submit()">
                </form>
            </label>
        </p>
    </div>
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    <form action="{{ route('stock.openingSave') }}" method="POST">
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>SKU</th>
                    <th>Opening Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>
                        <input type="hidden" name="stocks[{{ $loop->index }}][product_id]" value="{{ $product->id }}">
                        <input type="number" name="stocks[{{ $loop->index }}][quantity]" value="{{ $product->opening_stock ?? 0 }}" class="form-control" min="0">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <button class="btn btn-success">Save Opening Stock</button>
    </form>
</div>
@endsection