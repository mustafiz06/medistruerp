@extends('layout')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Purchase Order List:') }}</h3>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped data_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('PO Number') }}</th>
                                        <th>{{ __('Supplier') }}</th>
                                        <th>{{ __('Total Amount') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($purchaseOrders as $id=>$po)
                                    <tr>
                                        <td>
                                            {{ $loop->index+1 }}
                                        </td>
                                        <td>{{$po->order_date}}</td>
                                        <td><a href="#">{{$po->po_number}}</a></td>
                                        <td>{{ $po->supplier->name ?? '-' }}</td>
                                        <td>{{$po->total_amount}}</td>
                                        <td>

                                            <a href=""
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </a>


                                            <form id="deleteform" class="d-inline-block" action="{{ route('po.destroy', $po->id ) }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $po->id }}">
                                                <button type="submit" class="btn btn-danger btn-sm" id="delete">
                                                    <i class="fas fa-trash"></i>{{ __('Delete') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->

</section>
@endsection