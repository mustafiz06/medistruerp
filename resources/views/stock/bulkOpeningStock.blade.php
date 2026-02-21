@extends('layout')
@section('content')
<div class="container my-4">
    <h4>Opening Stock</h4>
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