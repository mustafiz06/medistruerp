@extends('layout')

@section('content')

<section class="content">
<div class="container-fluid">

<div class="card card-danger card-outline">

    <div class="card-header">
        <h3 class="card-title">Purchase Return List</h3>
    </div>

    <div class="card-body table-responsive">

        <table class="table table-bordered table-striped data_table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Return Date</th>
                    <th>Against PO Number</th>
                    <th>Supplier</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <th width="120">Action</th>
                </tr>
            </thead>

            <tbody>

                @foreach($returns as $return)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>
                        {{ \Carbon\Carbon::parse($return->return_date)->format('d M Y') }}
                    </td>

                    <td>
                        <a href="{{ route('po.view', $return->purchaseOrder->id) }}">
                            {{ $return->purchaseOrder->po_number }}
                        </a>
                    </td>

                    <td>
                        {{ $return->purchaseOrder->supplier->name ?? '-' }}
                    </td>

                    <td>
                        {{ $return->product->name ?? '-' }}
                    </td>

                    <td>
                        {{ $return->quantity }}
                    </td>

                    <td>
                        ৳ {{ number_format($return->unit_price, 2) }}
                    </td>

                    <td>
                        <strong>
                            ৳ {{ number_format($return->total, 2) }}
                        </strong>
                    </td>

                    <td>

                        {{-- Optional Delete --}}
                        <form action="#"
                              method="POST"
                              class="d-inline-block">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-danger btn-sm" disabled>
                                <i class="fas fa-trash"></i>
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
</section>

@endsection