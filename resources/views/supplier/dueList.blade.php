@extends('layout')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">

                    <div class="card-header">
                        <h3 class="card-title text-danger">
                            {{ __('Supplier Due List') }}
                        </h3>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-striped data_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Supplier Name') }}</th>
                                    <th>{{ __('Address') }}</th>
                                    <th>{{ __('Responsible Person') }}</th>
                                    <th>{{ __('Contact') }}</th>
                                    <th class="text-right">{{ __('Due Amount') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>

                            <tbody>

                                @php $totalDue = 0; @endphp

                                @foreach ($suppliers as $supplier)
                                @php $totalDue += $supplier->due_amount; @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $supplier->name }}</td>
                                    <td>{{ $supplier->address ?? '-' }}</td>
                                    <td>{{ $supplier->responsible_person ?? '-' }}</td>
                                    <td>{{ $supplier->responsible_person_contact ?? '-' }}</td>

                                    <td class="text-right text-danger font-weight-bold">
                                        {{ number_format($supplier->due_amount, 2) }}
                                    </td>

                                    <td>
                                        <a href="#"
                                   class="btn btn-success btn-sm">
                                    <i class="fas fa-money-bill"></i> Pay
                                </a>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>

                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right">
                                        {{ __('Total Due') }}
                                    </th>
                                    <th class="text-right text-danger">
                                        {{ number_format($totalDue, 2) }}
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>

                        </table>
                    </div>

                </div>
            </div>
        </div>
</section>
@endsection