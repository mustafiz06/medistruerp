@extends('layout')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('supplier List:') }}</h3>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped data_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Supplier Name') }}</th>
                                        <th>{{ __('Address') }}</th>
                                        <th>{{ __('Assistant Name') }}</th>
                                        <th>{{ __('Assistant Contact') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($suppliers as $id=>$supplier)
                                    <tr>
                                        <td>
                                            {{ $loop->index+1 }}
                                        </td>
                                        <td>{{$supplier->name}}</td>
                                        <td>{{$supplier->address}}</td>
                                        <td>{{$supplier->responsible_person}}</td>
                                        <td>{{$supplier->responsible_person_contact}}</td>
                                        <td>

                                            <a href="{{ route('supplier.edit.view', $supplier->id) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </a>


                                            <form id="deleteform" class="d-inline-block" action="{{ route('supplier.delete', $supplier->id ) }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $supplier->id }}">
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