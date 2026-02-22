@extends('layout')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('customer List:') }}</h3>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped data_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Designation') }}</th>
                                        <th>{{ __('Address') }}</th>
                                        <th>{{ __('Contact') }}</th>
                                        <th>{{ __('Assistant Name') }}</th>
                                        <th>{{ __('Assistant Contact') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($customers as $id=>$customer)
                                    <tr>
                                        <td>
                                            {{ $loop->index+1 }}
                                        </td>
                                        <td>{{$customer->name}}</td>
                                        <td>{{$customer->designation}}</td>
                                        <td>{{$customer->address}}</td>
                                        <td>{{$customer->contact}}</td>
                                        <td>{{$customer->responsible_person}}</td>
                                        <td>{{$customer->responsible_person_contact}}</td>
                                        <td>

                                            <a href="{{ route('customer.edit.view', $customer->id) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </a>


                                            <form id="deleteform" class="d-inline-block" action="{{ route('customer.delete', $customer->id ) }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $customer->id }}">
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
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->

</section>
@endsection