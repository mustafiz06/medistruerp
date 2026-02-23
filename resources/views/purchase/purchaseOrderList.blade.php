@extends('layout')

@section('content')

<section class="content">
    <div class="container-fluid">

        ```
        <div class="row">
            <div class="col-md-12">

                <div class="card card-primary card-outline">

                    <div class="card-header">
                        <h3 class="card-title">Purchase Order List</h3>
                    </div>

                    <div class="card-body table-responsive">

                        <table class="table table-bordered table-striped data_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>PO Number</th>
                                    <th>Supplier</th>
                                    <th>Total Amount</th>
                                    <th width="150">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($purchaseOrders as $po)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($po->order_date)->format('d M Y') }}
                                    </td>

                                    <td>
                                        <a href="{{ route('po.view', $po->id) }}">{{ $po->po_number }}</a>

                                    </td>

                                    <td>
                                        {{ $po->supplier->name ?? '-' }}
                                    </td>

                                    <td>
                                        ৳ {{ number_format($po->total_amount, 2) }}
                                    </td>

                                    <td>

                                        {{-- Edit --}}
                                        <a href="#"
                                            class="btn btn-info btn-sm">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a href="{{ route('po.return.form', $po->id) }}" class="btn btn-warning btn-sm">
                                            Return
                                        </a>

                                        {{-- Delete --}}
                                        <form class="d-inline-block"
                                            action="{{ route('po.destroy', $po->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure to delete this PO?')">

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
        </div>

    </div>

</section>

@endsection